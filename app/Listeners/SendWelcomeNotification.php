<?php

namespace App\Listeners;

use App\Events\StudentCreated;
use Illuminate\Support\Facades\Log;

class SendWelcomeNotification
{
    public function handle(StudentCreated $event): void
    {
        Log::info("Welcome notification sent for student: {$event->student->full_name}");
    }
}
