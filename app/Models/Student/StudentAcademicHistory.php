<?php

namespace App\Models\Student;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class StudentAcademicHistory extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'student_id', 'institution_name', 'institution_address',
        'from_date', 'to_date', 'class_name', 'percentage', 'grade',
        'board', 'remarks',
    ];
    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'percentage' => 'float',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('institution_name', 'like', "%{$search}%");
    }
}
