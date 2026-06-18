<?php

namespace App\Console\Commands;

use App\Models\Attendance\LeaveBalance;
use App\Models\Hr\Employee;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessLeaveAccruals extends Command
{
    protected $signature = 'leave:process-accruals {--month= : The month to process (format: Y-m)}';
    protected $description = 'Process monthly leave accruals for all active employees';

    public function handle(): int
    {
        $period = $this->option('month') ? Carbon::parse($this->option('month').'-01') : now()->startOfMonth();
        $monthName = $period->format('F Y');
        $this->info("Processing leave accruals for {$monthName}...");

        $leaveTypes = [
            'casual' => 1.0,
            'sick' => 0.833,
            'annual' => 1.25,
        ];

        $employees = Employee::with('leaveBalances')->where('status', 'active')->where('date_of_joining', '<=', $period->copy()->endOfMonth())->get();

        if ($employees->isEmpty()) {
            $this->warn('No active employees found.');
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($employees->count());
        $bar->start();

        $processed = 0;

        foreach ($employees as $employee) {
            try {
                DB::beginTransaction();

                foreach ($leaveTypes as $type => $monthlyAccrual) {
                    $leaveBalance = LeaveBalance::firstOrNew([
                        'employee_id' => $employee->id,
                        'school_id' => $employee->school_id,
                        'leave_type' => $type,
                        'year' => $period->year,
                    ]);

                    if (!$leaveBalance->exists) {
                        $leaveBalance->total_days = config("school.hr.leave_types.{$type}", 0);
                        $leaveBalance->used_days = 0;
                        $leaveBalance->pending_days = 0;
                    }

                    $leaveBalance->accrued_days = ($leaveBalance->accrued_days ?? 0) + $monthlyAccrual;
                    $leaveBalance->available_days = $leaveBalance->total_days + ($leaveBalance->accrued_days ?? 0) - $leaveBalance->used_days;
                    $leaveBalance->save();
                }

                DB::commit();
                $processed++;
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Leave accrual failed for employee {$employee->id}: " . $e->getMessage());
                $this->error(" Failed for {$employee->full_name}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Leave accruals processed for {$processed} employees.");

        return self::SUCCESS;
    }
}
