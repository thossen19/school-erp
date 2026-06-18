<?php

namespace App\Models\Payroll;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class SalaryComponent extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'salary_structure_id', 'name', 'type',
        'amount', 'is_percentage', 'percentage_value',
        'is_taxable', 'is_fixed', 'sort_order',
    ];
    protected $casts = [
        'amount' => 'decimal:2',
        'is_percentage' => 'boolean',
        'percentage_value' => 'float',
        'is_taxable' => 'boolean',
        'is_fixed' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function salaryStructure()
    {
        return $this->belongsTo(SalaryStructure::class);
    }

    public function scopeEarnings($query)
    {
        return $query->where('type', 'earning');
    }

    public function scopeDeductions($query)
    {
        return $query->where('type', 'deduction');
    }
}
