<?php

namespace App\Listeners;

use App\Events\AdmissionApproved;
use Illuminate\Support\Facades\Log;

class SendApprovalNotification
{
    public function handle(AdmissionApproved $event): void
    {
        Log::info("Admission approval notification sent for form: {$event->admissionForm->id}");
    }
}
