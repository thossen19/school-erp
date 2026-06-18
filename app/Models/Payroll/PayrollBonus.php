<?php

namespace App\Models\Payroll;

use App\Models\Hr\Employee;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollBonus extends Model
{
    use HasFactory, SchoolScopeTrait;

    protected $fillable = [
        'school_id', 'employee_id', 'bonus_type', 'amount',
        'bonus_date', 'description', 'is_taxable', 'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'bonus_date' => 'date',
        'is_taxable' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
