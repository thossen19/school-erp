<?php

namespace App\Models\Student;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class StudentMedicalRecord extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'student_id', 'blood_group', 'height', 'weight',
        'allergies', 'medical_conditions', 'medications', 'immunization_records',
        'emergency_contact_name', 'emergency_contact_phone',
        'primary_care_physician', 'physician_phone', 'insurance_provider',
        'insurance_policy_no', 'remarks',
    ];
    protected $casts = [
        'allergies' => 'array',
        'medical_conditions' => 'array',
        'medications' => 'array',
        'immunization_records' => 'array',
        'height' => 'float',
        'weight' => 'float',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('remarks', 'like', "%{$search}%")->orWhere('blood_group', 'like', "%{$search}%");
        });
    }
}
