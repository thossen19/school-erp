<?php

namespace App\Models\Health;

use App\Models\Student\Student;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class VaccinationRecord extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'student_id', 'vaccine_name', 'dose_number',
        'vaccination_date', 'next_due_date', 'administered_by',
        'remarks', 'batch_number',
    ];

    protected $casts = [
        'vaccination_date' => 'date',
        'next_due_date' => 'date',
        'dose_number' => 'integer',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('next_due_date', '>=', now());
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('vaccine_name', 'like', "%{$term}%");
    }
}
