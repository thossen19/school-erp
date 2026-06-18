<?php

namespace App\Models\Student;

use App\Models\School;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class StudentTransfer extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'student_id', 'transfer_date', 'from_class_id',
        'to_class_id', 'from_section_id', 'to_section_id', 'transfer_reason',
        'transfer_certificate_no', 'remarks', 'status',
    ];
    protected $casts = [
        'transfer_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('transfer_reason', 'like', "%{$search}%");
    }
}
