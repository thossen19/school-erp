<?php

namespace App\Models\Asset;

use App\Models\Hr\Employee;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class AssetMaintenance extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'asset_id', 'maintenance_type', 'description',
        'maintenance_date', 'vendor', 'cost', 'performed_by',
        'next_maintenance_date', 'status', 'remarks',
    ];

    protected $casts = [
        'maintenance_date' => 'date',
        'cost' => 'decimal:2',
        'next_maintenance_date' => 'date',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('maintenance_type', 'like', "%{$term}%");
    }
}
