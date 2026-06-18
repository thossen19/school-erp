<?php

namespace App\Models\Assessment;

use App\Models\Student\Student;
use App\Models\Academic\Subject;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ContinuousAssessment extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'exam_id', 'student_id', 'subject_id', 'assessment_type',
        'title', 'max_marks', 'marks_obtained', 'percentage',
        'assessment_date', 'grading_system_id', 'grade', 'remarks',
    ];
    protected $casts = [
        'max_marks' => 'float',
        'marks_obtained' => 'float',
        'percentage' => 'float',
        'assessment_date' => 'date',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function gradingSystem()
    {
        return $this->belongsTo(GradingSystem::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('title', 'like', "%{$search}%");
    }
}
