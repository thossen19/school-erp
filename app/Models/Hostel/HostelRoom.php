<?php

namespace App\Models\Hostel;

use App\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class HostelRoom extends Model
{
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'hostel_id', 'room_number', 'floor', 'room_type',
        'capacity', 'occupied_beds', 'rent', 'is_attached_bathroom', 'status',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'occupied_beds' => 'integer',
        'rent' => 'decimal:2',
        'is_attached_bathroom' => 'boolean',
        'status' => 'boolean',
    ];

    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }

    public function beds()
    {
        return $this->hasMany(HostelBed::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeAvailable($query)
    {
        return $query->whereColumn('occupied_beds', '<', 'capacity');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('room_number', 'like', "%{$term}%");
    }
}
