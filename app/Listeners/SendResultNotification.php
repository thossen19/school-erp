<?php

namespace App\Listeners;

use App\Events\ExamResultPublished;
use Illuminate\Support\Facades\Log;

class SendResultNotification
{
    public function handle(ExamResultPublished $event): void
    {
        Log::info("Exam result notification sent for exam: {$event->exam->name}");
    }
}
