<?php

namespace App\Models\Student;

use App\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class StudentGroupMember extends Model
{
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'student_group_id', 'student_id', 'role', 'joined_at',
    ];
    protected $casts = [
        'joined_at' => 'datetime',
    ];

    public function group()
    {
        return $this->belongsTo(StudentGroup::class, 'student_group_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
