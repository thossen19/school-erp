<?php

namespace App\Models\Admission;

use App\Models\Academic\ClassModel;
use App\Models\Academic\Section;
use App\Models\Student\Student;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class AdmissionEnquiry extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'student_name', 'parent_name', 'parent_email', 'parent_phone',
        'class_id', 'section_id', 'academic_year_id', 'source', 'status',
        'notes', 'follow_up_date', 'assigned_to',
    ];
    protected $casts = [
        'follow_up_date' => 'datetime',
        'status' => 'string',
    ];

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['closed', 'rejected']);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $this->where('student_name', 'like', "%{$search}%")->orWhere('parent_name', 'like', "%{$search}%")->orWhere('parent_phone', 'like', "%{$search}%");
        });
    }
}
