<?php

namespace App\Models\Academic;

use App\Models\User;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class LessonPlan extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'class_id', 'section_id', 'subject_id', 'teacher_id',
        'title', 'description', 'objectives', 'materials',
        'activities', 'assessment_method', 'duration',
        'status', 'lesson_content', 'lesson_date',
    ];
    protected $casts = [
        'objectives' => 'array',
        'materials' => 'array',
        'duration' => 'integer',
        'lesson_date' => 'date',
    ];

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

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('title', 'like', "%{$search}%");
    }
}
