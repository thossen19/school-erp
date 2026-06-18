<?php

namespace App\Listeners;

use App\Events\AdmissionApproved;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CreateStudentAccount
{
    public function handle(AdmissionApproved $event): void
    {
        $form = $event->admissionForm;
        $student = $form->student;

        if ($student && !$student->user_id) {
            $user = User::create([
                'name' => $student->full_name,
                'email' => $student->email ?? "{$student->admission_no}@school.local",
                'password' => Hash::make('password123'),
                'school_id' => $student->school_id,
                'user_type' => 'student',
                'status' => true,
            ]);

            $student->update(['user_id' => $user->id]);
            Log::info("Student account created for {$student->full_name}");
        }
    }
}
