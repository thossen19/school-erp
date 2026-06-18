<?php

namespace App\Services\Payroll;

use App\Contracts\RepositoryInterface;
use App\Exceptions\ServiceException;
use App\Models\Payroll\Payroll;
use App\Repositories\Payroll\PayrollRepository;
use App\Repositories\Payroll\SalaryStructureRepository;
use App\Repositories\Payroll\LoanRequestRepository;
use App\Repositories\Payroll\OvertimeRecordRepository;
use App\Repositories\Hr\EmployeeRepository;
use App\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PayrollService extends BaseService
{
    protected PayrollRepository $payrollRepository;
    protected SalaryStructureRepository $salaryStructureRepository;
    protected LoanRequestRepository $loanRequestRepository;
    protected OvertimeRecordRepository $overtimeRepository;
    protected EmployeeRepository $employeeRepository;

    public function __construct(
        PayrollRepository $payrollRepository,
        SalaryStructureRepository $salaryStructureRepository,
        LoanRequestRepository $loanRequestRepository,
        OvertimeRecordRepository $overtimeRepository,
        EmployeeRepository $employeeRepository
    ) {
        parent::__construct();
        $this->payrollRepository = $payrollRepository;
        $this->salaryStructureRepository = $salaryStructureRepository;
        $this->loanRequestRepository = $loanRequestRepository;
        $this->overtimeRepository = $overtimeRepository;
        $this->employeeRepository = $employeeRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->payrollRepository;
    }

    public function createSalaryStructure(array $data): \App\Models\Payroll\SalaryStructure
    {
        return DB::transaction(function () use ($data) {
            $structure = $this->salaryStructureRepository->create($data);

            $this->logActivity('salary_structure_created', $structure);

            return $structure;
        });
    }

    public function processPayroll(int $month, int $year, ?array $employeeIds = null): Collection
    {
        return DB::transaction(function () use ($month, $year, $employeeIds) {
            $employees = $employeeIds
                ? collect($employeeIds)->map(fn($id) => $this->employeeRepository->getById($id))
                : $this->employeeRepository->getActiveEmployees();

            $processed = collect();
            foreach ($employees as $employee) {
                $salaryStructure = $this->salaryStructureRepository->findByEmployee($employee->id);

                if (!$salaryStructure) {
                    continue;
                }

                $loanDeduction = $this->loanRequestRepository->getTotalOutstandingByEmployee($employee->id);
                $monthlyLoanDeduction = $loanDeduction > 0 ? min($loanDeduction, $salaryStructure->basic_salary * 0.1) : 0;

                $overtimeHours = $this->overtimeRepository->getTotalOvertimeHours($employee->id, $month, $year);
                $overtimePay = $this->overtimeRepository->getTotalOvertimePay($employee->id, $month, $year);

                $grossSalary = $salaryStructure->basic_salary
                    + ($salaryStructure->allowances ?? 0)
                    + $overtimePay;

                $deductions = ($salaryStructure->deductions ?? 0)
                    + $monthlyLoanDeduction
                    + $this->calculateTax($grossSalary);

                $netSalary = $grossSalary - $deductions;

                $payroll = $this->payrollRepository->processPayroll([
                    'employee_id' => $employee->id,
                    'payroll_month' => $month,
                    'payroll_year' => $year,
                    'basic_salary' => $salaryStructure->basic_salary,
                    'allowances' => $salaryStructure->allowances ?? 0,
                    'overtime_pay' => $overtimePay,
                    'gross_salary' => $grossSalary,
                    'loan_deduction' => $monthlyLoanDeduction,
                    'tax_deduction' => $this->calculateTax($grossSalary),
                    'other_deductions' => $salaryStructure->deductions ?? 0,
                    'total_deductions' => $deductions,
                    'net_salary' => $netSalary,
                    'status' => 'pending',
                ]);

                $processed->push($payroll);
            }

            $this->logActivity('payroll_processed', ['month' => $month, 'year' => $year, 'count' => $processed->count()]);

            return $processed;
        });
    }

    public function generatePayslip(int $payrollId): Payroll
    {
        $payroll = $this->payrollRepository->getById($payrollId);

        $this->logActivity('payslip_generated', $payroll);

        return $payroll;
    }

    public function calculateTax(float $taxableIncome): float
    {
        $tax = 0;

        return match (true) {
            $taxableIncome <= 250000 => 0,
            $taxableIncome <= 500000 => ($taxableIncome - 250000) * 0.05,
            $taxableIncome <= 750000 => 12500 + ($taxableIncome - 500000) * 0.10,
            $taxableIncome <= 1000000 => 37500 + ($taxableIncome - 750000) * 0.15,
            $taxableIncome <= 1250000 => 75000 + ($taxableIncome - 1000000) * 0.20,
            $taxableIncome <= 1500000 => 125000 + ($taxableIncome - 1250000) * 0.25,
            default => 187500 + ($taxableIncome - 1500000) * 0.30,
        };
    }

    public function processLoan(array $data): \App\Models\Payroll\LoanRequest
    {
        return DB::transaction(function () use ($data) {
            $data['status'] = 'pending';
            $data['remaining_amount'] = $data['loan_amount'];
            $data['applied_at'] = now();

            $loan = $this->loanRequestRepository->create($data);

            $this->logActivity('loan_processed', $loan);

            return $loan;
        });
    }

    public function processOvertime(array $data): \App\Models\Payroll\OvertimeRecord
    {
        return DB::transaction(function () use ($data) {
            $data['status'] = 'pending';
            $data['overtime_amount'] = $data['overtime_hours'] * ($data['hourly_rate'] ?? 0);
            $data['date'] = $data['date'] ?? now();

            $overtime = $this->overtimeRepository->create($data);

            $this->logActivity('overtime_processed', $overtime);

            return $overtime;
        });
    }

    public function exportTally(int $month, int $year): array
    {
        $payrolls = $this->payrollRepository->getByMonth($month, $year);

        $tallyData = [
            'month' => $month,
            'year' => $year,
            'total_employees' => $payrolls->count(),
            'total_gross' => $payrolls->sum('gross_salary'),
            'total_deductions' => $payrolls->sum('total_deductions'),
            'total_net' => $payrolls->sum('net_salary'),
            'records' => $payrolls->map(fn($p) => [
                'employee' => $p->employee->full_name,
                'employee_no' => $p->employee->employee_no,
                'gross' => $p->gross_salary,
                'deductions' => $p->total_deductions,
                'net' => $p->net_salary,
            ]),
        ];

        $this->logActivity('tally_exported', ['month' => $month, 'year' => $year]);

        return $tallyData;
    }
}
