<?php

namespace App\Models\Academic;

use App\Models\Hr\Employee;
use App\Models\Academic\ClassModel;
use App\Models\Academic\Subject;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class StudyMaterial extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'class_id', 'subject_id', 'employee_id',
        'title', 'description', 'file_path', 'file_type',
        'file_size', 'is_public', 'status',
    ];
    protected $casts = [
        'file_size' => 'integer',
        'is_public' => 'boolean',
    ];

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
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
