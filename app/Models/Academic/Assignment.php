<?php

namespace App\Models\Academic;

use App\Models\Hr\Employee;
use App\Models\Academic\ClassModel;
use App\Models\Academic\Section;
use App\Models\Academic\Subject;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Assignment extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'class_id', 'section_id', 'subject_id',
        'employee_id', 'title', 'description', 'instructions',
        'max_marks', 'due_date', 'assigned_date',
        'attachment_path', 'status',
    ];
    protected $casts = [
        'max_marks' => 'integer',
        'due_date' => 'datetime',
        'assigned_date' => 'date',
    ];

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

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
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
