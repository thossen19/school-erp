<?php

namespace App\Models\Hostel;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Hostel extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'name', 'code', 'type', 'address',
        'city', 'email', 'phone', 'warden_id', 'total_rooms',
        'total_beds', 'status',
    ];

    protected $casts = [
        'total_rooms' => 'integer',
        'total_beds' => 'integer',
        'status' => 'boolean',
    ];

    public function warden()
    {
        return $this->belongsTo(\App\Models\Hr\Employee::class, 'warden_id');
    }

    public function rooms()
    {
        return $this->hasMany(HostelRoom::class);
    }

    public function beds()
    {
        return $this->hasManyThrough(HostelBed::class, HostelRoom::class);
    }

    public function allocations()
    {
        return $this->hasMany(HostelAllocation::class, 'hostel_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")->orWhere('code', 'like', "%{$term}%");
        });
    }
}
