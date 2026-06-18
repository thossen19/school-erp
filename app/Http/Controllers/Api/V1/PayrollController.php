<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payroll\StorePayrollRequest;
use App\Models\Payroll\Payroll;
use App\Services\Payroll\PayrollService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    use ApiResponseTrait;

    protected PayrollService $payrollService;

    public function __construct(PayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    public function index(Request $request): JsonResponse
    {
        $payrolls = Payroll::with('employee:id,first_name,last_name,employee_no')->when($request->employee_id, fn($q) => $q->where('employee_id', $request->employee_id))->when($request->month, fn($q) => $q->where('month', $request->month))->when($request->year, fn($q) => $q->where('year', $request->year))->when($request->status, fn($q) => $q->where('status', $request->status))->orderBy('year', 'desc')->orderBy('month', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($payrolls, 'Payroll records retrieved');
    }

    public function store(StorePayrollRequest $request): JsonResponse
    {
        $payroll = Payroll::create($request->validated());
        return $this->createdResponse($payroll->load('employee'), 'Payroll record created');
    }

    public function processPayroll(Request $request): JsonResponse
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2099',
            'employee_ids' => 'nullable|array',
            'employee_ids.*' => 'integer|exists:employees,id',
        ]);

        $result = $this->payrollService->processPayroll($request->month, $request->year, $request->employee_ids);
        return $this->successResponse($result, 'Payroll processed');
    }

    public function generatePayslip(int $id): JsonResponse
    {
        $payroll = Payroll::with('employee', 'employee.department', 'employee.designation')->findOrFail($id);
        $payslip = $this->payrollService->generatePayslip($payroll);
        return $this->successResponse($payslip, 'Payslip generated');
    }

    public function getByEmployee(int $employeeId, Request $request): JsonResponse
    {
        $payrolls = Payroll::where('employee_id', $employeeId)->when($request->year, fn($q) => $q->where('year', $request->year))->orderBy('year', 'desc')->orderBy('month', 'desc')->get();

        $summary = [
            'total_earned' => $payrolls->sum('net_salary'),
            'total_deductions' => $payrolls->sum('total_deductions'),
            'average_monthly' => $payrolls->count() > 0 ? round($payrolls->avg('net_salary'), 2) : 0,
        ];

        return $this->successResponse(['records' => $payrolls, 'summary' => $summary], 'Payroll by employee');
    }

    public function getReport(Request $request): JsonResponse
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2099',
        ]);

        $report = $this->payrollService->getPayrollReport($request->month, $request->year);
        return $this->successResponse($report, 'Payroll report generated');
    }

    public function exportTally(Request $request): JsonResponse
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2099',
        ]);

        $export = $this->payrollService->exportToTally($request->month, $request->year);
        return $this->successResponse($export, 'Tally export generated');
    }
}
