<?php

namespace App\Models\Transport;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class TransportRoute extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'route_name', 'route_number', 'start_point',
        'end_point', 'distance_km', 'total_stops', 'status',
    ];

    protected $casts = [
        'distance_km' => 'float',
        'total_stops' => 'integer',
        'status' => 'boolean',
    ];

    public function stops()
    {
        return $this->hasMany(TransportRouteStop::class);
    }

    public function vehicles()
    {
        return $this->belongsToMany(TransportVehicle::class, 'transport_route_vehicle')->withPivot('trip_type', 'time')->withTimestamps();
    }

    public function allocations()
    {
        return $this->hasMany(TransportAllocation::class);
    }

    public function fees()
    {
        return $this->hasMany(TransportFee::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('route_name', 'like', "%{$term}%")->orWhere('route_number', 'like', "%{$term}%");
        });
    }
}
