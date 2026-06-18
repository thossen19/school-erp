<?php

namespace App\Models\Attendance;

use App\Models\Hr\Employee;
use App\Models\Student\Student;
use App\Models\User;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class LeaveBalance extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'leave_type_id', 'student_id', 'employee_id',
        'academic_year_id', 'total_days', 'used_days', 'remaining_days',
        'carried_forward',
    ];
    protected $casts = [
        'total_days' => 'integer',
        'used_days' => 'integer',
        'remaining_days' => 'integer',
        'carried_forward' => 'integer',
    ];

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
