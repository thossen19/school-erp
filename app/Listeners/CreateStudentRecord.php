<?php

namespace App\Listeners;

use App\Events\StudentCreated;

class CreateStudentRecord
{
    public function handle(StudentCreated $event): void
    {
        // Create associated records for the new student
        $event->student->timeline()->create([
            'student_id' => $event->student->id,
            'type' => 'admission',
            'title' => 'Student admitted',
            'description' => 'New student record created',
            'date' => now(),
        ]);
    }
}
