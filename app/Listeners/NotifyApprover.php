<?php

namespace App\Listeners;

use App\Events\LeaveApplied;
use Illuminate\Support\Facades\Log;

class NotifyApprover
{
    public function handle(LeaveApplied $event): void
    {
        Log::info("Leave approval notification sent for request: {$event->leaveRequest->id}");
    }
}
