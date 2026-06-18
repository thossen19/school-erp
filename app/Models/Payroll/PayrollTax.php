<?php

namespace App\Models\Payroll;

use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollTax extends Model
{
    use HasFactory, SchoolScopeTrait;

    protected $fillable = [
        'school_id', 'name', 'code', 'type', 'rate',
        'min_amount', 'max_amount', 'is_active',
        'effective_from', 'effective_to', 'description',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'effective_from' => 'date',
        'effective_to' => 'date',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
