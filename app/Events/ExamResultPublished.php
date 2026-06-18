<?php

namespace App\Events;

use App\Models\Assessment\Exam;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExamResultPublished
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Exam $exam;

    public function __construct(Exam $exam)
    {
        $this->exam = $exam;
    }
}
