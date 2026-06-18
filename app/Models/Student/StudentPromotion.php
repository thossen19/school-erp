<?php

namespace App\Models\Student;

use App\Models\Academic\AcademicYear;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class StudentPromotion extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'student_id', 'from_class_id', 'to_class_id',
        'from_section_id', 'to_section_id', 'from_academic_year_id',
        'to_academic_year_id', 'promotion_date', 'status', 'remarks',
    ];
    protected $casts = [
        'promotion_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function fromAcademicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'from_academic_year_id');
    }

    public function toAcademicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'to_academic_year_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('remarks', 'like', "%{$search}%");
    }
}
