<?php

namespace App\Models\Assessment;

use App\Models\Academic\ClassModel;
use App\Models\Academic\Section;
use App\Models\Academic\Subject;
use App\Models\Hr\Employee;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ExamSchedule extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'exam_id', 'subject_id', 'class_id', 'section_id',
        'exam_date', 'start_time', 'end_time', 'total_marks', 'passing_marks',
        'room_number', 'invigilator_id', 'notes',
    ];
    protected $casts = [
        'exam_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'total_marks' => 'integer',
        'passing_marks' => 'integer',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function invigilator()
    {
        return $this->belongsTo(Employee::class, 'invigilator_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('notes', 'like', "%{$search}%");
    }
}
