<?php

namespace App\Models\Assessment;

use App\Models\Academic\ClassModel;
use App\Models\Academic\Subject;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineExamination extends Model
{
    use HasFactory, SchoolScopeTrait;

    protected $fillable = [
        'school_id', 'title', 'description', 'class_id', 'subject_id',
        'duration_minutes', 'total_marks', 'passing_marks',
        'start_time', 'end_time', 'status', 'is_published', 'instructions',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'total_marks' => 'integer',
        'passing_marks' => 'integer',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function class()
    {
        return $this->belongsTo(ClassModel::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
