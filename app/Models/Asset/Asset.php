<?php

namespace App\Models\Asset;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Asset extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'asset_category_id', 'name', 'code', 'description',
        'purchase_date', 'purchase_price', 'current_value',
        'salvage_value', 'useful_life_years', 'depreciation_method',
        'depreciation_rate', 'condition', 'location',
        'serial_number', 'manufacturer', 'model', 'warranty_expiry',
        'is_allocated', 'status',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2',
        'current_value' => 'decimal:2',
        'salvage_value' => 'decimal:2',
        'useful_life_years' => 'integer',
        'depreciation_rate' => 'float',
        'warranty_expiry' => 'date',
        'is_allocated' => 'boolean',
        'status' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(AssetCategory::class, 'asset_category_id');
    }

    public function allocations()
    {
        return $this->hasMany(AssetAllocation::class);
    }

    public function maintenances()
    {
        return $this->hasMany(AssetMaintenance::class);
    }

    public function depreciations()
    {
        return $this->hasMany(AssetDepreciation::class);
    }

    public function audits()
    {
        return $this->hasMany(AssetAudit::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_allocated', false);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")->orWhere('code', 'like', "%{$term}%")->orWhere('serial_number', 'like', "%{$term}%");
        });
    }
}
