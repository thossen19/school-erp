<?php

namespace App\Observers;

use App\Events\StudentCreated;
use App\Models\Student\Student;

class StudentObserver
{
    public function created(Student $student): void
    {
        if (!$student->admission_no) {
            $student->admission_no = 'STU-' . now()->year . '-' . str_pad($student->id, 5, '0', STR_PAD_LEFT);
            $student->saveQuietly();
        }

        event(new StudentCreated($student));
    }

    public function updated(Student $student): void
    {
        //
    }

    public function deleted(Student $student): void
    {
        //
    }

    public function restored(Student $student): void
    {
        //
    }

    public function forceDeleted(Student $student): void
    {
        //
    }
}
