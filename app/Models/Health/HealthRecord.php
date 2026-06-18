<?php

namespace App\Models\Health;

use App\Models\Student\Student;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class HealthRecord extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'student_id', 'height', 'weight', 'bmi',
        'blood_pressure', 'vision_left', 'vision_right', 'dental_health',
        'checkup_date', 'notes', 'conducted_by', 'allergies',
    ];

    protected $casts = [
        'checkup_date' => 'date',
        'height' => 'float',
        'weight' => 'float',
        'bmi' => 'float',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('notes', 'like', "%{$term}%");
    }
}
