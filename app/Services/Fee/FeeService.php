<?php

namespace App\Services\Fee;

use App\Contracts\RepositoryInterface;
use App\Exceptions\ServiceException;
use App\Models\Fee\FeeCollection;
use App\Models\Fee\FeeStructure;
use App\Repositories\Fee\FeeStructureRepository;
use App\Repositories\Fee\FeeCollectionRepository;
use App\Repositories\Fee\FeeInstallmentRepository;
use App\Repositories\Fee\FeeDiscountRepository;
use App\Repositories\Fee\FeeConcessionRepository;
use App\Repositories\Fee\FeeDueTrackingRepository;
use App\Repositories\Fee\FeeCategoryRepository;
use App\Repositories\Student\StudentRepository;
use App\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FeeService extends BaseService
{
    protected FeeStructureRepository $feeStructureRepository;
    protected FeeCollectionRepository $feeCollectionRepository;
    protected FeeInstallmentRepository $installmentRepository;
    protected FeeDiscountRepository $discountRepository;
    protected FeeConcessionRepository $concessionRepository;
    protected FeeDueTrackingRepository $dueTrackingRepository;
    protected FeeCategoryRepository $categoryRepository;
    protected StudentRepository $studentRepository;

    public function __construct(
        FeeStructureRepository $feeStructureRepository,
        FeeCollectionRepository $feeCollectionRepository,
        FeeInstallmentRepository $installmentRepository,
        FeeDiscountRepository $discountRepository,
        FeeConcessionRepository $concessionRepository,
        FeeDueTrackingRepository $dueTrackingRepository,
        FeeCategoryRepository $categoryRepository,
        StudentRepository $studentRepository
    ) {
        $this->feeStructureRepository = $feeStructureRepository;
        $this->feeCollectionRepository = $feeCollectionRepository;
        $this->installmentRepository = $installmentRepository;
        $this->discountRepository = $discountRepository;
        $this->concessionRepository = $concessionRepository;
        $this->dueTrackingRepository = $dueTrackingRepository;
        $this->categoryRepository = $categoryRepository;
        $this->studentRepository = $studentRepository;
        parent::__construct();
    }

    public function repository(): RepositoryInterface
    {
        return $this->feeStructureRepository;
    }

    public function createFeeStructure(array $data): FeeStructure
    {
        return DB::transaction(function () use ($data) {
            $structure = $this->feeStructureRepository->create($data);

            if (isset($data['installments']) && is_array($data['installments'])) {
                foreach ($data['installments'] as $installment) {
                    $installment['fee_structure_id'] = $structure->id;
                    $this->installmentRepository->create($installment);
                }
            }

            $this->logActivity('fee_structure_created', $structure);

            return $structure;
        });
    }

    public function generateInstallments(int $feeStructureId, array $installments): Collection
    {
        return DB::transaction(function () use ($feeStructureId, $installments) {
            $structure = $this->feeStructureRepository->getById($feeStructureId);

            $created = collect();
            foreach ($installments as $installment) {
                $installment['fee_structure_id'] = $feeStructureId;
                $created->push($this->installmentRepository->create($installment));
            }

            $this->logActivity('installments_generated', [
                'fee_structure_id' => $feeStructureId,
                'count' => $created->count(),
            ]);

            return $created;
        });
    }

    public function collectPayment(array $data): FeeCollection
    {
        return DB::transaction(function () use ($data) {
            $student = $this->studentRepository->getById($data['student_id']);

            if (!isset($data['receipt_no'])) {
                $data['receipt_no'] = $this->generateReceiptNo();
            }
            $data['payment_date'] = $data['payment_date'] ?? now();
            $data['status'] = $data['paid_amount'] >= ($data['total_amount'] ?? 0) ? 'paid' : 'partial';
            $data['balance_amount'] = ($data['total_amount'] ?? 0) - ($data['paid_amount'] ?? 0);

            $collection = $this->feeCollectionRepository->recordPayment($data);

            if (isset($data['installment_ids'])) {
                foreach ($data['installment_ids'] as $installmentId) {
                    $this->installmentRepository->markAsPaid($installmentId);
                }
            }

            $this->logActivity('fee_payment_collected', $collection);

            return $collection;
        });
    }

    public function generateReceipt(int $collectionId): ?FeeCollection
    {
        $collection = $this->feeCollectionRepository->getById($collectionId);

        if (!$collection->receipt_no) {
            $receiptNo = $this->generateReceiptNo();
            $this->feeCollectionRepository->update($collectionId, ['receipt_no' => $receiptNo]);
            $collection = $collection->fresh();
        }

        return $collection;
    }

    public function calculateLateFee(int $installmentId, float $lateFeeRate = 0.0): array
    {
        $installment = $this->installmentRepository->getById($installmentId);

        if ($installment->status === 'paid') {
            return ['late_fee' => 0, 'days_overdue' => 0, 'message' => 'Installment already paid'];
        }

        $dueDate = \Carbon\Carbon::parse($installment->due_date);
        $now = now();

        if ($now->lte($dueDate)) {
            return ['late_fee' => 0, 'days_overdue' => 0, 'message' => 'Not overdue yet'];
        }

        $daysOverdue = $dueDate->diffInDays($now);
        $lateFee = $lateFeeRate > 0
            ? ($installment->amount * ($lateFeeRate / 100) * $daysOverdue)
            : 0;

        return [
            'late_fee' => round($lateFee, 2),
            'days_overdue' => $daysOverdue,
            'installment_amount' => $installment->amount,
            'total_due' => round($installment->amount + $lateFee, 2),
        ];
    }

    public function applyDiscount(array $data): \App\Models\Fee\FeeDiscount
    {
        return DB::transaction(function () use ($data) {
            $discount = $this->discountRepository->create($data);

            $this->logActivity('fee_discount_applied', $discount);

            return $discount;
        });
    }

    public function applyConcession(int $studentId, int $feeStructureId, array $data): \App\Models\Fee\FeeConcession
    {
        return DB::transaction(function () use ($studentId, $feeStructureId, $data) {
            $concession = $this->concessionRepository->applyConcession($studentId, $feeStructureId, $data);

            $this->logActivity('fee_concession_applied', $concession);

            return $concession;
        });
    }

    public function sendFeeReminder(int $studentId): bool
    {
        try {
            $student = $this->studentRepository->getById($studentId);
            $due = $this->feeCollectionRepository->getOutstandingByStudent($studentId);

            activity()->causedBy(auth()->user())->performedOn($student)->withProperties(['outstanding_amount' => $due, 'sent_at' => now()])->event('fee_reminder')->log("FeeReminder: Reminder sent for student {$studentId}, outstanding: {$due}");

            return true;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('FeeService@sendFeeReminder: ' . $e->getMessage());
            return false;
        }
    }

    public function getFeeReport(int $classId, ?int $sectionId, string $startDate, string $endDate): array
    {
        $collections = $this->feeCollectionRepository->getByDateRange($startDate, $endDate);

        if ($classId) {
            $collections = $collections->filter(function ($c) use ($classId) {
                return $c->student && $c->student->class_id == $classId;
            });
        }
        if ($sectionId) {
            $collections = $collections->filter(function ($c) use ($sectionId) {
                return $c->student && $c->student->section_id == $sectionId;
            });
        }

        $report = [
            'total_collected' => $collections->sum('paid_amount'),
            'total_pending' => $collections->sum('balance_amount'),
            'total_transactions' => $collections->count(),
            'by_payment_mode' => $collections->groupBy('payment_mode')->map(fn($g) => $g->sum('paid_amount')),
        ];

        $this->logActivity('fee_report_viewed', $report);

        return $report;
    }

    public function getStudentDue(int $studentId): array
    {
        $student = $this->studentRepository->getById($studentId);

        $installments = $this->installmentRepository->findByFeeStructure(
            $this->feeStructureRepository->findByClassAndYear($student->class_id, $student->academic_year_id)->id
        );

        $collections = $this->feeCollectionRepository->getByStudent($studentId);
        $totalPaid = $collections->sum('paid_amount');
        $totalDue = $installments->sum('amount');

        return [
            'student' => $student->toArray(),
            'total_fee' => $totalDue,
            'total_paid' => $totalPaid,
            'balance' => $totalDue - $totalPaid,
            'installments' => $installments,
        ];
    }

    public function reconcilePayment(int $collectionId, array $data): FeeCollection
    {
        return DB::transaction(function () use ($collectionId, $data) {
            $collection = $this->feeCollectionRepository->getById($collectionId);

            $collection = $this->feeCollectionRepository->update($collectionId, [
                'reconciled' => true,
                'reconciled_at' => now(),
                'reconciled_by' => auth()->id(),
                'reconciliation_notes' => $data['notes'] ?? null,
            ]);

            $this->logActivity('payment_reconciled', $collection);

            return $collection;
        });
    }

    protected function generateReceiptNo(): string
    {
        $last = $this->feeCollectionRepository->query()->whereYear('created_at', now()->year)->orderBy('id', 'desc')->first();

        $nextNumber = $last ? ((int) substr($last->receipt_no, -5)) + 1 : 1;

        return 'RCPT-' . now()->year . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }
}
