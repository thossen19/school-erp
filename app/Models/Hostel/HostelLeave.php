<?php

namespace App\Models\Hostel;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class HostelLeave extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'hostel_allocation_id', 'student_id',
        'from_date', 'to_date', 'reason', 'parent_approval',
        'warden_approval', 'checkout_time', 'return_time',
        'emergency_contact', 'status', 'remarks',
    ];

    protected $casts = [
        'from_date' => 'datetime',
        'to_date' => 'datetime',
        'checkout_time' => 'datetime',
        'return_time' => 'datetime',
    ];

    public function allocation()
    {
        return $this->belongsTo(HostelAllocation::class, 'hostel_allocation_id');
    }

    public function student()
    {
        return $this->belongsTo(Student\Student::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
