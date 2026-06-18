<?php

namespace App\Listeners;

use App\Events\AttendanceMarked;
use Illuminate\Support\Facades\Log;

class SendParentNotification
{
    public function handle(AttendanceMarked $event): void
    {
        Log::info("Attendance notification sent for student: {$event->attendance->student_id}");
    }
}
