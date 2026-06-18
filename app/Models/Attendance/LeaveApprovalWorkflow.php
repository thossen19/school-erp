<?php

namespace App\Models\Attendance;

use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApprovalWorkflow extends Model
{
    use HasFactory, SchoolScopeTrait;

    protected $fillable = [
        'school_id', 'leave_type_id', 'name', 'approval_level',
        'approver_type', 'approver_id', 'condition_field',
        'condition_operator', 'condition_value', 'is_active', 'notes',
    ];

    protected $casts = [
        'approval_level' => 'integer',
        'is_active' => 'boolean',
    ];

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
