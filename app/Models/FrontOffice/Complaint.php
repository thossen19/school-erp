<?php

namespace App\Models\FrontOffice;

use App\Models\Student\Student;
use App\Models\Hr\Employee;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Complaint extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'complainant_name', 'complainant_type',
        'complainant_phone', 'complainant_email', 'student_id',
        'complaint_type', 'description', 'assigned_to',
        'priority', 'complaint_date', 'resolution_date',
        'resolution_notes', 'status',
    ];

    protected $casts = [
        'complaint_date' => 'datetime',
        'resolution_date' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('complainant_name', 'like', "%{$term}%")->orWhere('complaint_type', 'like', "%{$term}%");
        });
    }
}
