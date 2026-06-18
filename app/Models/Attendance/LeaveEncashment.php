<?php

namespace App\Models\Attendance;

use App\Models\Hr\Employee;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveEncashment extends Model
{
    use HasFactory, SchoolScopeTrait;

    protected $fillable = [
        'school_id', 'employee_id', 'leave_type_id', 'days_encashed',
        'amount_per_day', 'total_amount', 'encashment_date',
        'status', 'remarks', 'approved_by',
    ];

    protected $casts = [
        'days_encashed' => 'integer',
        'amount_per_day' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'encashment_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }
}
