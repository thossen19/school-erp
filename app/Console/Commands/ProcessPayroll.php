<?php

namespace App\Console\Commands;

use App\Models\Hr\Employee;
use App\Models\Payroll\Payroll;
use App\Models\Payroll\SalaryStructure;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessPayroll extends Command
{
    protected $signature = 'payroll:process {--month= : The month to process (format: Y-m)}';
    protected $description = 'Process payroll for all active employees';

    public function handle(): int
    {
        $period = $this->option('month') ? Carbon::parse($this->option('month').'-01') : now()->startOfMonth();
        $monthName = $period->format('F Y');
        $this->info("Processing payroll for {$monthName}...");

        $employees = Employee::with('salaryStructure', 'leaveBalances', 'overtimeRecords')->where('status', 'active')->get();

        if ($employees->isEmpty()) {
            $this->warn('No active employees found.');
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($employees->count());
        $bar->start();

        $processed = 0;
        $skipped = 0;

        foreach ($employees as $employee) {
            try {
                DB::beginTransaction();

                $existing = Payroll::where('employee_id', $employee->id)->whereMonth('period', $period->month)->whereYear('period', $period->year)->first();

                if ($existing) {
                    $this->line(" Payroll already exists for {$employee->full_name}, skipping.");
                    $skipped++;
                    DB::rollBack();
                    $bar->advance();
                    continue;
                }

                $salaryStructure = $employee->salaryStructure;
                $basicSalary = $salaryStructure?->basic_salary ?? 0;
                $allowances = $salaryStructure?->allowances ?? [];
                $deductions = $salaryStructure?->deductions ?? [];

                $totalAllowances = is_array($allowances) ? array_sum(array_column($allowances, 'amount')) : 0;
                $totalDeductions = is_array($deductions) ? array_sum(array_column($deductions, 'amount')) : 0;

                $leaveDeductions = $this->calculateLeaveDeductions($employee, $period);
                $overtimePay = $this->calculateOvertimePay($employee, $period);

                $grossSalary = $basicSalary + $totalAllowances + $overtimePay;
                $totalDeductionsAmount = $totalDeductions + $leaveDeductions;
                $netSalary = $grossSalary - $totalDeductionsAmount;

                Payroll::create([
                    'employee_id' => $employee->id,
                    'school_id' => $employee->school_id,
                    'period' => $period->format('Y-m-d'),
                    'basic_salary' => $basicSalary,
                    'allowances' => $allowances,
                    'deductions' => array_merge($deductions ?? [], ['leave' => $leaveDeductions]),
                    'overtime_pay' => $overtimePay,
                    'gross_salary' => $grossSalary,
                    'total_deductions' => $totalDeductionsAmount,
                    'net_salary' => $netSalary,
                    'status' => 'processed',
                    'processed_by' => 1,
                    'processed_at' => now(),
                ]);

                DB::commit();
                $processed++;
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Payroll processing failed for employee {$employee->id}: " . $e->getMessage());
                $this->error(" Failed to process payroll for {$employee->full_name}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Payroll for {$monthName} completed: {$processed} processed, {$skipped} skipped.");

        return self::SUCCESS;
    }

    protected function calculateLeaveDeductions($employee, Carbon $period): float
    {
        $unpaidLeaves = $employee->leaveRequests()->where('status', 'approved')->where('type', 'unpaid')->whereMonth('start_date', $period->month)->whereYear('start_date', $period->year)->sum('total_days');

        if ($unpaidLeaves <= 0) {
            return 0;
        }

        $dailyRate = ($employee->salaryStructure?->basic_salary ?? 0) / 30;
        return round($dailyRate * $unpaidLeaves, 2);
    }

    protected function calculateOvertimePay($employee, Carbon $period): float
    {
        $overtimeRecords = $employee->overtimeRecords()->where('status', 'approved')->whereMonth('date', $period->month)->whereYear('date', $period->year)->get();

        $totalHours = $overtimeRecords->sum('hours');
        $hourlyRate = ($employee->salaryStructure?->basic_salary ?? 0) / (30 * 8);

        return round($hourlyRate * $totalHours * 1.5, 2);
    }
}
