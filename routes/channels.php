<?php

use App\Models\Student\Student;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Private user channel - each user gets their own notifications
Broadcast::channel('user.{userId}', function (User $user, int $userId) {
    return (int) $user->id === (int) $userId;
});

// School-wide announcements
Broadcast::channel('school.{schoolId}', function (User $user, int $schoolId) {
    return (int) ($user->school_id ?? 0) === (int) $schoolId;
});

// Class-specific channel (for teachers and students)
Broadcast::channel('class.{classId}', function (User $user, int $classId) {
    if ($user->user_type === 'teacher') {
        return true;
    }
    if ($user->user_type === 'student' && $user->student) {
        return (int) $user->student->class_id === (int) $classId;
    }
    return in_array($user->user_type, ['super_admin', 'school_admin', 'principal']);
});

// Student-specific parent channel
Broadcast::channel('student.{studentId}.parents', function (User $user, int $studentId) {
    if ($user->user_type === 'parent' && $user->parent) {
        return $user->parent->children()->where('student_id', $studentId)->exists();
    }
    return false;
});

// Department channel
Broadcast::channel('department.{departmentId}', function (User $user, int $departmentId) {
    if ($user->employee) {
        return (int) $user->employee->department_id === (int) $departmentId;
    }
    return in_array($user->user_type, ['super_admin', 'school_admin']);
});

// Attendance updates
Broadcast::channel('attendance.{classId}', function (User $user, int $classId) {
    return in_array($user->user_type, ['super_admin', 'school_admin', 'principal', 'teacher']);
});

// Fee updates
Broadcast::channel('fees.{schoolId}', function (User $user, int $schoolId) {
    return (int) ($user->school_id ?? 0) === (int) $schoolId
        && in_array($user->user_type, ['super_admin', 'school_admin', 'accountant']);
});

// Exam results
Broadcast::channel('exam.{examId}.results', function (User $user, int $examId) {
    return in_array($user->user_type, ['super_admin', 'school_admin', 'principal', 'teacher']);
});

// Presence channel for online users
Broadcast::channel('online-users.{schoolId}', function (User $user, int $schoolId) {
    if ((int) ($user->school_id ?? 0) === (int) $schoolId) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'user_type' => $user->user_type,
        ];
    }
    return false;
});

// Transport tracking channel
Broadcast::channel('transport.{vehicleId}', function (User $user, int $vehicleId) {
    return in_array($user->user_type, ['super_admin', 'school_admin', 'transport_manager', 'parent']);
});

// Notice board / announcements
Broadcast::channel('notices.{schoolId}', function (User $user, int $schoolId) {
    return (int) ($user->school_id ?? 0) === (int) $schoolId;
});
