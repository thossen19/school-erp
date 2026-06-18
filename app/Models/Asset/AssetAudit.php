<?php

namespace App\Models\Asset;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class AssetAudit extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'asset_id', 'audit_date', 'auditor_name',
        'physical_condition', 'location_verified', 'expected_location',
        'actual_location', 'is_damaged', 'damage_description',
        'is_missing', 'recommendation', 'status', 'notes',
    ];

    protected $casts = [
        'audit_date' => 'date',
        'location_verified' => 'boolean',
        'is_damaged' => 'boolean',
        'is_missing' => 'boolean',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('auditor_name', 'like', "%{$term}%");
    }
}
