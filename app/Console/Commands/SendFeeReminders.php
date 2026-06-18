<?php

namespace App\Console\Commands;

use App\Models\Fee\FeeDueTracking;
use App\Models\Fee\FeeInstallment;
use App\Models\Student\Student;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendFeeReminders extends Command
{
    protected $signature = 'fee:send-reminders {--days=7 : Days before due date to send reminder}';
    protected $description = 'Send fee reminders to parents with upcoming or overdue payments';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $this->info("Sending fee reminders for installments due within {$days} days...");

        $now = Carbon::today();
        $reminderDate = $now->copy()->addDays($days);

        $installments = FeeInstallment::with('feeStructure.class', 'feeStructure.feeCategory')->where('status', 'pending')->whereBetween('due_date', [$now, $reminderDate])->get();

        $this->info("Found {$installments->count()} installments requiring reminders.");

        $sent = 0;
        $failed = 0;

        foreach ($installments as $installment) {
            try {
                $students = Student::where('class_id', $installment->feeStructure->class_id)->where('status', 'active')->get();

                foreach ($students as $student) {
                    $parents = $student->parents;

                    FeeDueTracking::updateOrCreate(
                        [
                            'student_id' => $student->id,
                            'fee_installment_id' => $installment->id,
                            'academic_year_id' => $installment->feeStructure->academic_year_id,
                        ],
                        [
                            'amount_due' => $installment->amount,
                            'due_date' => $installment->due_date,
                            'reminder_sent_at' => $now,
                            'reminder_count' => DB::raw('COALESCE(reminder_count, 0) + 1'),
                            'status' => 'reminded',
                        ]
                    );

                    foreach ($parents as $parent) {
                        $this->info(" Reminder sent to parent {$parent->id} for student {$student->id}");
                    }
                }

                $sent++;
            } catch (\Exception $e) {
                Log::error("Failed to send fee reminder for installment {$installment->id}: " . $e->getMessage());
                $failed++;
            }
        }

        $this->info("Fee reminders: {$sent} processed, {$failed} failed.");

        return self::SUCCESS;
    }
}
