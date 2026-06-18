<?php

namespace App\Models\Admission;

use App\Models\Academic\ClassModel;
use App\Models\Academic\Section;
use App\Models\Academic\AcademicYear;
use App\Models\Student\Student;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class AdmissionForm extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'admission_no', 'student_name', 'date_of_birth', 'gender',
        'blood_group', 'nationality', 'religion', 'caste_category',
        'father_name', 'father_phone', 'father_email', 'father_occupation',
        'mother_name', 'mother_phone', 'mother_email', 'mother_occupation',
        'guardian_name', 'guardian_relation', 'guardian_phone', 'guardian_email',
        'address', 'city', 'state', 'country', 'pincode',
        'class_id', 'section_id', 'academic_year_id', 'application_date',
        'status', 'remarks',
    ];
    protected $casts = [
        'date_of_birth' => 'date',
        'application_date' => 'date',
    ];

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $this->where('admission_no', 'like', "%{$search}%")->orWhere('student_name', 'like', "%{$search}%")->orWhere('father_phone', 'like', "%{$search}%");
        });
    }
}
