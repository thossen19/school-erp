<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Payroll\LoanRequest;
use App\Services\Payroll\LoanService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    use ApiResponseTrait;

    protected LoanService $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    public function index(Request $request): JsonResponse
    {
        $loans = LoanRequest::with('employee:id,first_name,last_name,employee_no')->when($request->employee_id, fn($q) => $q->where('employee_id', $request->employee_id))->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->type, fn($q) => $q->where('loan_type', $request->type))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($loans, 'Loan requests retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|integer|exists:employees,id',
            'loan_type' => 'required|string|in:personal,education,home,vehicle,emergency,other',
            'amount' => 'required|numeric|min:0',
            'purpose' => 'required|string|max:1000',
            'repayment_months' => 'required|integer|min:1|max:120',
            'monthly_installment' => 'required|numeric|min:0',
            'interest_rate' => 'nullable|numeric|min:0|max:100',
            'guarantor' => 'nullable|string|max:255',
            'documents' => 'nullable|array',
        ]);

        $loan = LoanRequest::create(array_merge($validated, ['status' => 'pending']));
        return $this->createdResponse($loan->load('employee'), 'Loan request submitted');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            LoanRequest::with('employee', 'installments')->findOrFail($id),
            'Loan request retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $loan = LoanRequest::findOrFail($id);
        $validated = $request->validate([
            'amount' => 'sometimes|numeric|min:0',
            'purpose' => 'sometimes|string|max:1000',
            'repayment_months' => 'sometimes|integer|min:1|max:120',
            'monthly_installment' => 'sometimes|numeric|min:0',
        ]);
        $loan->update($validated);
        return $this->updatedResponse($loan->fresh()->load('employee'), 'Loan request updated');
    }

    public function destroy(int $id): JsonResponse
    {
        LoanRequest::findOrFail($id)->delete();
        return $this->deletedResponse('Loan request deleted');
    }

    public function approve(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'approval_date' => 'required|date',
            'approved_by' => 'nullable|integer|exists:employees,id',
            'remarks' => 'nullable|string|max:500',
        ]);

        $loan = LoanRequest::findOrFail($id);
        $loan->update(array_merge($request->validated(), ['status' => 'approved']));
        $this->loanService->generateInstallments($loan);

        return $this->successResponse($loan->load('employee', 'installments'), 'Loan approved');
    }

    public function deductInstallment(Request $request, int $id): JsonResponse
    {
        $result = $this->loanService->deductInstallment($id);
        return $this->successResponse($result, 'Installment deducted');
    }
}
