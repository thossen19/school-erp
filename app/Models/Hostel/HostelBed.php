<?php

namespace App\Models\Hostel;

use App\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class HostelBed extends Model
{
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'hostel_room_id', 'bed_number', 'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function room()
    {
        return $this->belongsTo(HostelRoom::class, 'hostel_room_id');
    }

    public function allocation()
    {
        return $this->hasOne(HostelAllocation::class, 'bed_id');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'maintenance');
    }
}
