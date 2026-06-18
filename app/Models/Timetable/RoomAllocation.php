<?php

namespace App\Models\Timetable;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class RoomAllocation extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'room_name', 'room_number', 'building', 'floor',
        'capacity', 'room_type', 'is_available', 'timetable_period_id',
        'notes',
    ];
    protected $casts = [
        'capacity' => 'integer',
        'floor' => 'integer',
        'is_available' => 'boolean',
    ];

    public function timetablePeriod()
    {
        return $this->belongsTo(TimetablePeriod::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $this->where('room_name', 'like', "%{$search}%")->orWhere('room_number', 'like', "%{$search}%");
        });
    }
}
