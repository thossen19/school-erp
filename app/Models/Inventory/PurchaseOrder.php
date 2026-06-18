<?php

namespace App\Models\Inventory;

use App\Models\Accounting\AccountsPayable;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class PurchaseOrder extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'vendor_id', 'order_no', 'order_date',
        'expected_delivery_date', 'delivery_date', 'subtotal',
        'tax_amount', 'discount_amount', 'total_amount',
        'paid_amount', 'balance_amount', 'notes', 'status',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'delivery_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function accountsPayable()
    {
        return $this->hasOne(AccountsPayable::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('order_no', 'like', "%{$term}%");
    }
}
