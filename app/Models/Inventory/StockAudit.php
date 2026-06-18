<?php

namespace App\Models\Inventory;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class StockAudit extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'item_id', 'audit_date', 'expected_quantity',
        'actual_quantity', 'difference', 'unit_price', 'difference_value',
        'reason', 'adjusted_by', 'status', 'notes',
    ];

    protected $casts = [
        'audit_date' => 'date',
        'expected_quantity' => 'integer',
        'actual_quantity' => 'integer',
        'difference' => 'integer',
        'unit_price' => 'decimal:2',
        'difference_value' => 'decimal:2',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function scopeDiscrepancy($query)
    {
        return $query->where('difference', '!=', 0);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('reason', 'like', "%{$term}%");
    }
}
