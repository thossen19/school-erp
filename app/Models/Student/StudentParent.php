<?php

namespace App\Models\Student;

use App\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class StudentParent extends Model
{
    use HasFactory, AuditableTrait;

    protected $table = 'student_parents';

    protected $fillable = [
        'student_id', 'parent_id', 'relationship', 'is_emergency_contact',
    ];
    protected $casts = [
        'is_emergency_contact' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function parent()
    {
        return $this->belongsTo(Guardian::class);
    }
}
