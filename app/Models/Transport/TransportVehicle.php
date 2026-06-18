<?php

namespace App\Models\Transport;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class TransportVehicle extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'vehicle_number', 'registration_number', 'vehicle_model',
        'manufacturer', 'year_of_manufacture', 'capacity',
        'fuel_type', 'insurance_expiry', 'fitness_expiry',
        'last_service_date', 'next_service_date', 'status',
    ];

    protected $casts = [
        'year_of_manufacture' => 'integer',
        'capacity' => 'integer',
        'insurance_expiry' => 'date',
        'fitness_expiry' => 'date',
        'last_service_date' => 'date',
        'next_service_date' => 'date',
        'status' => 'boolean',
    ];

    public function routes()
    {
        return $this->belongsToMany(TransportRoute::class, 'transport_route_vehicle')->withPivot('trip_type', 'time')->withTimestamps();
    }

    public function driver()
    {
        return $this->hasOne(TransportDriver::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('vehicle_number', 'like', "%{$term}%")->orWhere('registration_number', 'like', "%{$term}%");
        });
    }
}
