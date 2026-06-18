<?php

namespace App\Models\Fee;

use App\Models\Student\Student;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ScholarshipStudent extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'scholarship_id', 'student_id', 'academic_year_id',
        'awarded_amount', 'awarded_date', 'approved_by', 'status', 'remarks',
    ];
    protected $casts = [
        'awarded_amount' => 'decimal:2',
        'awarded_date' => 'date',
    ];

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
