<?php

namespace App\Models\Assessment;

use App\Models\Student\Student;
use App\Models\Academic\Subject;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ExamResult extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'exam_id', 'exam_schedule_id', 'student_id',
        'subject_id', 'marks_obtained', 'total_marks', 'percentage',
        'grade', 'grade_point', 'remarks', 'is_absent', 'status',
    ];
    protected $casts = [
        'marks_obtained' => 'float',
        'total_marks' => 'float',
        'percentage' => 'float',
        'grade_point' => 'float',
        'is_absent' => 'boolean',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function examSchedule()
    {
        return $this->belongsTo(ExamSchedule::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function scopePassed($query)
    {
        return $query->where('status', 'passed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('remarks', 'like', "%{$search}%");
    }
}
