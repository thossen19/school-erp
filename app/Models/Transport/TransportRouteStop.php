<?php

namespace App\Models\Transport;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class TransportRouteStop extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'transport_route_id', 'stop_name', 'stop_order',
        'latitude', 'longitude', 'landmark', 'distance_from_school',
        'pickup_time', 'drop_time', 'status',
    ];

    protected $casts = [
        'stop_order' => 'integer',
        'latitude' => 'float',
        'longitude' => 'float',
        'distance_from_school' => 'float',
        'pickup_time' => 'datetime',
        'drop_time' => 'datetime',
        'status' => 'boolean',
    ];

    public function route()
    {
        return $this->belongsTo(TransportRoute::class, 'transport_route_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeByOrder($query)
    {
        return $query->orderBy('stop_order');
    }
}
