<?php

namespace App\Listeners;

use App\Events\AttendanceMarked;

class UpdateAnalytics
{
    public function handle(AttendanceMarked $event): void
    {
        // Update attendance analytics in cache or database
        cache()->forget('attendance_analytics_' . $event->attendance->school_id);
    }
}
