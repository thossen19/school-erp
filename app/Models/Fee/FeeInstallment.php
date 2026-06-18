<?php

namespace App\Models\Fee;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class FeeInstallment extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'fee_structure_id', 'name', 'amount', 'due_date',
        'late_fee_amount', 'late_fee_applicable', 'status',
    ];
    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'late_fee_amount' => 'decimal:2',
        'late_fee_applicable' => 'boolean',
    ];

    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'pending');
    }
}
