<?php

namespace App\Models\Fee;

use App\Models\Academic\ClassModel;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class FeeDiscount extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'fee_structure_id', 'name', 'code', 'type',
        'value', 'is_percentage', 'applicable_to', 'max_discount_amount',
        'valid_from', 'valid_until', 'status',
    ];
    protected $casts = [
        'value' => 'decimal:2',
        'is_percentage' => 'boolean',
        'max_discount_amount' => 'decimal:2',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'status' => 'boolean',
    ];

    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $this->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%");
        });
    }
}
