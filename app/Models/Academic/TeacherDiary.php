<?php

namespace App\Models\Academic;

use App\Models\Hr\Employee;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class TeacherDiary extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'employee_id', 'class_id', 'section_id',
        'subject_id', 'date', 'period_number', 'topic_covered',
        'teaching_method', 'student_engagement', 'notes',
        'homework_given', 'next_plan',
    ];
    protected $casts = [
        'date' => 'date',
        'period_number' => 'integer',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('topic_covered', 'like', "%{$search}%");
    }
}
