<?php

namespace App\Models\Inventory;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Vendor extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'name', 'code', 'email', 'phone', 'website',
        'contact_person', 'contact_person_phone', 'contact_person_email',
        'address', 'city', 'state', 'country', 'pincode',
        'gst_number', 'pan_number', 'payment_terms',
        'is_active', 'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")->orWhere('code', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%");
        });
    }
}
