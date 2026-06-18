<?php

namespace App\Services\Payroll;

use App\Contracts\RepositoryInterface;
use App\Exceptions\ServiceException;
use App\Models\Payroll\LoanRequest;
use App\Repositories\Payroll\LoanRequestRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class LoanService extends BaseService
{
    protected LoanRequestRepository $loanRequestRepository;

    public function __construct(LoanRequestRepository $loanRequestRepository)
    {
        parent::__construct();
        $this->loanRequestRepository = $loanRequestRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->loanRequestRepository;
    }

    public function applyLoan(array $data): LoanRequest
    {
        return DB::transaction(function () use ($data) {
            $data['status'] = 'pending';
            $data['remaining_amount'] = $data['loan_amount'];
            $data['applied_at'] = now();

            if (!isset($data['monthly_installment'])) {
                $data['monthly_installment'] = $data['loan_amount'] / ($data['tenure_months'] ?? 12);
            }

            $loan = $this->loanRequestRepository->create($data);

            $this->logActivity('loan_applied', $loan);

            return $loan;
        });
    }

    public function approveLoan(int $id, ?string $approvedBy = null): LoanRequest
    {
        return DB::transaction(function () use ($id, $approvedBy) {
            $loan = $this->loanRequestRepository->getById($id);

            if ($loan->status !== 'pending') {
                throw new ServiceException("Loan is already {$loan->status}.");
            }

            $this->loanRequestRepository->approveLoan($id, $approvedBy);

            $loan = $loan->fresh();

            $this->logActivity('loan_approved', $loan);

            return $loan;
        });
    }

    public function deductInstallment(int $loanId): array
    {
        return DB::transaction(function () use ($loanId) {
            $loan = $this->loanRequestRepository->getById($loanId);

            if ($loan->status !== 'approved' && $loan->status !== 'disbursed') {
                throw new ServiceException("Loan is not active.");
            }

            if ($loan->remaining_amount <= 0) {
                throw new ServiceException("Loan is already fully paid.");
            }

            $installmentAmount = min($loan->monthly_installment, $loan->remaining_amount);
            $newRemaining = $loan->remaining_amount - $installmentAmount;

            $updateData = [
                'remaining_amount' => $newRemaining,
                'last_installment_date' => now(),
                'total_paid' => ($loan->total_paid ?? 0) + $installmentAmount,
            ];

            if ($newRemaining <= 0) {
                $updateData['status'] = 'closed';
                $updateData['closed_at'] = now();
            }

            $this->loanRequestRepository->update($loanId, $updateData);
            $loan = $loan->fresh();

            $this->logActivity('loan_installment_deducted', [
                'loan_id' => $loanId,
                'amount' => $installmentAmount,
                'remaining' => $loan->remaining_amount,
            ]);

            return [
                'loan' => $loan,
                'installment_amount' => $installmentAmount,
                'remaining_amount' => $loan->remaining_amount,
                'is_fully_paid' => $loan->remaining_amount <= 0,
            ];
        });
    }

    public function getLoanSummary(int $employeeId): array
    {
        $loans = $this->loanRequestRepository->findByEmployee($employeeId);

        $totalApproved = $loans->whereIn('status', ['approved', 'disbursed'])->sum('loan_amount');
        $totalRemaining = $loans->whereIn('status', ['approved', 'disbursed'])->sum('remaining_amount');
        $totalPaid = $loans->whereIn('status', ['approved', 'disbursed', 'closed'])->sum('total_paid');

        return [
            'employee_id' => $employeeId,
            'total_loans' => $loans->count(),
            'active_loans' => $loans->whereIn('status', ['approved', 'disbursed'])->count(),
            'total_approved' => $totalApproved,
            'total_remaining' => $totalRemaining,
            'total_paid' => $totalPaid,
            'loans' => $loans,
        ];
    }
}
