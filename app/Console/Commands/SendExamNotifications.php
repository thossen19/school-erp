<?php

namespace App\Console\Commands;

use App\Models\Assessment\Exam;
use App\Models\Assessment\ExamResult;
use App\Models\Student\Student;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendExamNotifications extends Command
{
    protected $signature = 'exam:send-notifications {--exam-id= : Send notifications for a specific exam}';
    protected $description = 'Send exam result notifications to students and parents';

    public function handle(): int
    {
        $this->info('Sending exam result notifications...');

        $exams = $this->option('exam-id')
            ? Exam::where('id', $this->option('exam-id'))->get()
            : Exam::where('is_published', true)->whereDoesntHave('results', function ($q) {
                    $q->whereNotNull('notified_at');
                })->get();

        if ($exams->isEmpty()) {
            $this->info('No exams require notifications.');
            return self::SUCCESS;
        }

        $notified = 0;
        $failed = 0;

        foreach ($exams as $exam) {
            $this->info("Processing notifications for exam: {$exam->name}");

            $results = ExamResult::with('student.parents')->where('exam_id', $exam->id)->whereNull('notified_at')->get();

            foreach ($results as $result) {
                try {
                    $student = $result->student;
                    if (!$student) {
                        continue;
                    }

                    $result->update(['notified_at' => now()]);

                    $this->line(" Notification queued for student {$student->full_name}");
                    $notified++;
                } catch (\Exception $e) {
                    Log::error("Failed to send exam notification for result {$result->id}: " . $e->getMessage());
                    $failed++;
                }
            }
        }

        $this->info("Exam notifications: {$notified} sent, {$failed} failed.");

        return self::SUCCESS;
    }
}
