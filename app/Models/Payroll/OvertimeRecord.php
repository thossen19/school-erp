<?php

namespace App\Models\Payroll;

use App\Models\Hr\Employee;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class OvertimeRecord extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'employee_id', 'date', 'hours', 'rate',
        'amount', 'approved_by', 'status', 'end_time',
    ];

    protected $casts = [
        'date' => 'date',
        'hours' => 'float',
        'rate' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeSearch($query, $search)
    {
        return $query->whereHas('employee', fn($q) => $q->where('first_name', 'like', "%{$search}%"));
    }
}
