<?php

namespace App\Services\Admission;

use App\Contracts\RepositoryInterface;
use App\Exceptions\ServiceException;
use App\Models\Admission\AdmissionForm;
use App\Repositories\Admission\AdmissionFormRepository;
use App\Repositories\Admission\EntranceExamResultRepository;
use App\Repositories\Admission\MeritListRepository;
use App\Repositories\Admission\WaitingListRepository;
use App\Repositories\Student\StudentRepository;
use App\Repositories\Student\StudentParentRepository;
use App\Repositories\Fee\FeeCollectionRepository;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdmissionService extends BaseService
{
    protected AdmissionFormRepository $admissionFormRepository;
    protected EntranceExamResultRepository $examResultRepository;
    protected MeritListRepository $meritListRepository;
    protected WaitingListRepository $waitingListRepository;
    protected StudentRepository $studentRepository;
    protected StudentParentRepository $parentRepository;
    protected FeeCollectionRepository $feeCollectionRepository;

    public function __construct(
        AdmissionFormRepository $admissionFormRepository,
        EntranceExamResultRepository $examResultRepository,
        MeritListRepository $meritListRepository,
        WaitingListRepository $waitingListRepository,
        StudentRepository $studentRepository,
        StudentParentRepository $parentRepository,
        FeeCollectionRepository $feeCollectionRepository
    ) {
        $this->admissionFormRepository = $admissionFormRepository;
        $this->examResultRepository = $examResultRepository;
        $this->meritListRepository = $meritListRepository;
        $this->waitingListRepository = $waitingListRepository;
        $this->studentRepository = $studentRepository;
        $this->parentRepository = $parentRepository;
        $this->feeCollectionRepository = $feeCollectionRepository;
        parent::__construct();
    }

    public function repository(): RepositoryInterface
    {
        return $this->admissionFormRepository;
    }

    public function createApplication(array $data): AdmissionForm
    {
        return DB::transaction(function () use ($data) {
            $data['application_no'] = $data['application_no'] ?? $this->generateApplicationNo();
            $data['status'] = $data['status'] ?? 'draft';
            $data['submitted_at'] = $data['submitted_at'] ?? now();

            $form = $this->admissionFormRepository->create($data);

            $this->logActivity('application_created', $form);

            return $form;
        });
    }

    public function processApplication(int $id, array $data): AdmissionForm
    {
        return DB::transaction(function () use ($id, $data) {
            $form = $this->admissionFormRepository->getById($id);

            if ($form->status !== 'draft' && $form->status !== 'pending') {
                throw new ServiceException("Application is already in {$form->status} status.");
            }

            $data['status'] = 'under_review';
            $data['reviewed_at'] = $data['reviewed_at'] ?? now();

            $form = $this->admissionFormRepository->update($id, $data);

            $this->logActivity('application_processed', $form);

            return $form;
        });
    }

    public function approveAdmission(int $id, array $data = []): AdmissionForm
    {
        return DB::transaction(function () use ($id, $data) {
            $form = $this->admissionFormRepository->getById($id);

            if ($form->status !== 'under_review') {
                throw new ServiceException("Application must be under review before approval.");
            }

            $form = $this->admissionFormRepository->update($id, [
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => $data['approved_by'] ?? auth()->id(),
                'remarks' => $data['remarks'] ?? null,
            ]);

            $this->logActivity('admission_approved', $form);

            return $form;
        });
    }

    public function rejectAdmission(int $id, string $reason): AdmissionForm
    {
        return DB::transaction(function () use ($id, $reason) {
            $form = $this->admissionFormRepository->getById($id);

            if ($form->status === 'approved') {
                throw new ServiceException("Cannot reject an already approved application.");
            }

            $form = $this->admissionFormRepository->update($id, [
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejection_reason' => $reason,
            ]);

            $this->logActivity('admission_rejected', $form);

            return $form;
        });
    }

    public function generateMeritList(int $examId, ?int $limit = null): Collection
    {
        return DB::transaction(function () use ($examId, $limit) {
            $results = $this->examResultRepository->getRankings($examId);

            $rankings = $results->values()->map(function ($result, $index) use ($examId) {
                return [
                    'entrance_exam_id' => $examId,
                    'student_id' => $result->student_id,
                    'total_marks' => $result->total_marks,
                    'rank' => $index + 1,
                    'is_confirmed' => false,
                ];
            });

            if ($limit) {
                $rankings = $rankings->take($limit);
            }

            foreach ($rankings as $ranking) {
                $this->meritListRepository->firstOrCreate(
                    ['entrance_exam_id' => $examId, 'student_id' => $ranking['student_id']],
                    $ranking
                );
            }

            $meritList = $this->meritListRepository->findByExam($examId);

            $this->logActivity('merit_list_generated', ['exam_id' => $examId, 'count' => $meritList->count()]);

            return $meritList;
        });
    }

    public function scheduleInterview(int $id, array $data): AdmissionForm
    {
        return DB::transaction(function () use ($id, $data) {
            $form = $this->admissionFormRepository->getById($id);

            $form = $this->admissionFormRepository->update($id, [
                'interview_date' => $data['interview_date'],
                'interview_time' => $data['interview_time'] ?? null,
                'interview_venue' => $data['interview_venue'] ?? null,
                'interviewer_id' => $data['interviewer_id'] ?? null,
                'status' => 'interview_scheduled',
                'remarks' => $data['remarks'] ?? null,
            ]);

            $this->logActivity('interview_scheduled', $form);

            return $form;
        });
    }

    public function manageWaitingList(int $admissionFormId, array $data): Model
    {
        return DB::transaction(function () use ($admissionFormId, $data) {
            $form = $this->admissionFormRepository->getById($admissionFormId);

            $waitingEntry = $this->waitingListRepository->firstOrCreate(
                ['admission_form_id' => $admissionFormId],
                [
                    'admission_form_id' => $admissionFormId,
                    'class_id' => $form->class_id,
                    'academic_year_id' => $form->academic_year_id,
                    'priority' => $data['priority'] ?? $this->waitingListRepository->count(['class_id' => $form->class_id]) + 1,
                    'status' => $data['status'] ?? 'waiting',
                    'remarks' => $data['remarks'] ?? null,
                ]
            );

            if (isset($data['status']) && $data['status'] !== $waitingEntry->status) {
                $this->waitingListRepository->update($waitingEntry->id, ['status' => $data['status']]);
                $waitingEntry = $waitingEntry->fresh();
            }

            $this->logActivity('waiting_list_managed', $waitingEntry);

            return $waitingEntry;
        });
    }

    public function collectAdmissionFee(int $admissionFormId, array $paymentData): Model
    {
        return DB::transaction(function () use ($admissionFormId, $paymentData) {
            $form = $this->admissionFormRepository->getById($admissionFormId);

            if ($form->status !== 'approved') {
                throw new ServiceException("Admission must be approved before fee collection.");
            }

            $paymentData['student_id'] = $form->student_id ?? $paymentData['student_id'];
            $paymentData['payment_date'] = $paymentData['payment_date'] ?? now();
            $paymentData['receipt_no'] = $paymentData['receipt_no'] ?? $this->generateReceiptNo();

            $collection = $this->feeCollectionRepository->recordPayment($paymentData);

            $this->admissionFormRepository->update($admissionFormId, [
                'fee_paid' => true,
                'fee_paid_at' => now(),
                'status' => 'fee_collected',
            ]);

            $this->logActivity('admission_fee_collected', $collection);

            return $collection;
        });
    }

    public function generateStudentId(int $admissionFormId, ?array $studentData = null): Model
    {
        return DB::transaction(function () use ($admissionFormId, $studentData) {
            $form = $this->admissionFormRepository->getById($admissionFormId);

            $data = $studentData ?? [
                'first_name' => $form->applicant_name,
                'admission_no' => $this->generateAdmissionNo(),
                'class_id' => $form->class_id,
                'academic_year_id' => $form->academic_year_id,
                'status' => 'active',
            ];

            if (!isset($data['admission_no'])) {
                $data['admission_no'] = $this->generateAdmissionNo();
            }

            $student = $this->studentRepository->create($data);

            $this->admissionFormRepository->update($admissionFormId, [
                'student_id' => $student->id,
                'status' => 'student_created',
            ]);

            $this->logActivity('student_id_generated', $student);

            return $student;
        });
    }

    public function registerParent(int $studentId, array $parentData): Model
    {
        return DB::transaction(function () use ($studentId, $parentData) {
            $student = $this->studentRepository->getById($studentId);

            $parent = $this->parentRepository->firstOrCreate(
                ['email' => $parentData['email'] ?? null],
                $parentData
            );

            if ($student->parents()->where('parent_id', $parent->id)->doesntExist()) {
                $student->parents()->attach($parent->id, ['relationship' => $parentData['relationship'] ?? 'guardian']);
            }

            $this->logActivity('parent_registered', $parent);

            return $parent;
        });
    }

    protected function generateApplicationNo(): string
    {
        $last = $this->admissionFormRepository->query()->whereYear('created_at', now()->year)->orderBy('id', 'desc')->first();

        $nextNumber = $last ? ((int) substr($last->application_no, -5)) + 1 : 1;

        return 'APP-' . now()->year . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    protected function generateReceiptNo(): string
    {
        return 'RCP-' . now()->format('YmdHis') . '-' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
    }

    protected function generateAdmissionNo(): string
    {
        $last = $this->studentRepository->query()->whereYear('created_at', now()->year)->orderBy('id', 'desc')->first();

        $nextNumber = $last ? ((int) substr($last->admission_no, -5)) + 1 : 1;

        return 'ADM-' . now()->year . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }
}
