<?php

namespace App\Models\Transport;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class TransportTracking extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'transport_vehicle_id', 'transport_route_id',
        'tracking_date', 'latitude', 'longitude', 'speed',
        'fuel_level', 'engine_status', 'ignition_status',
        'odometer_reading', 'location', 'notes',
    ];

    protected $casts = [
        'tracking_date' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float',
        'speed' => 'float',
        'fuel_level' => 'float',
        'odometer_reading' => 'float',
        'ignition_status' => 'boolean',
    ];

    public function vehicle()
    {
        return $this->belongsTo(TransportVehicle::class, 'transport_vehicle_id');
    }

    public function route()
    {
        return $this->belongsTo(TransportRoute::class, 'transport_route_id');
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('tracking_date', $date);
    }
}
