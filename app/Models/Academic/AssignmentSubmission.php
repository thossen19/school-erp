<?php

namespace App\Models\Academic;

use App\Models\Student\Student;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class AssignmentSubmission extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'assignment_id', 'student_id', 'submission_date',
        'content', 'attachment_path', 'marks_obtained', 'teacher_remarks',
        'is_late', 'status',
    ];
    protected $casts = [
        'submission_date' => 'datetime',
        'marks_obtained' => 'float',
        'is_late' => 'boolean',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeGraded($query)
    {
        return $query->where('status', 'graded');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('teacher_remarks', 'like', "%{$search}%");
    }
}
