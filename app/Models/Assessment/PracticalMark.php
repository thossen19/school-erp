<?php

namespace App\Models\Assessment;

use App\Models\Academic\Subject;
use App\Models\Student\Student;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticalMark extends Model
{
    use HasFactory, SchoolScopeTrait;

    protected $fillable = [
        'school_id', 'student_id', 'subject_id', 'exam_id',
        'marks_obtained', 'total_marks', 'grade', 'remarks',
        'practical_date', 'is_absent', 'status',
    ];

    protected $casts = [
        'marks_obtained' => 'decimal:2',
        'total_marks' => 'decimal:2',
        'practical_date' => 'date',
        'is_absent' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
