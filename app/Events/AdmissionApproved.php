<?php

namespace App\Events;

use App\Models\Admission\AdmissionForm;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdmissionApproved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public AdmissionForm $admissionForm;

    public function __construct(AdmissionForm $admissionForm)
    {
        $this->admissionForm = $admissionForm;
    }
}
