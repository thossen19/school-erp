<?php

namespace App\Models\Payroll;

use App\Models\Hr\Employee;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Payroll extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'branch_id', 'employee_id', 'salary_structure_id',
        'month', 'year', 'total_earnings', 'total_deductions',
        'gross_salary', 'net_salary', 'tax_amount', 'payment_date',
        'payment_method', 'status', 'payroll_period', 'bonus_amount', 'transaction_id',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'total_earnings' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'bonus_amount' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function salaryStructure()
    {
        return $this->belongsTo(SalaryStructure::class);
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('payroll_period', 'like', "%{$search}%");
    }
}
