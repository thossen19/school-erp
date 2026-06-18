<?php

namespace App\Models\Assessment;

use App\Models\Academic\Subject;
use App\Models\Student\Student;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiEvaluation extends Model
{
    use HasFactory, SchoolScopeTrait;

    protected $fillable = [
        'school_id', 'student_id', 'evaluation_type', 'subject_id',
        'input_content', 'ai_response', 'score', 'feedback',
        'metrics', 'status',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'metrics' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
