<?php

namespace App\Models\Academic;

use App\Models\Hr\Employee;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Subject extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'name', 'code', 'type', 'description',
        'max_marks', 'passing_marks', 'is_lab', 'status',
    ];
    protected $casts = [
        'max_marks' => 'integer',
        'passing_marks' => 'integer',
        'is_lab' => 'boolean',
        'status' => 'boolean',
    ];

    public function classes()
    {
        return $this->belongsToMany(ClassModel::class, 'class_subjects', 'subject_id', 'class_id')->withPivot('is_compulsory', 'max_students_per_week')->withTimestamps();
    }

    public function teachers()
    {
        return $this->belongsToMany(Employee::class, 'teacher_subjects', 'subject_id', 'teacher_id')->withPivot('class_id', 'section_id')->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $this->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%");
        });
    }
}
