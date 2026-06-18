<?php

namespace App\Models\Alumni;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class AlumniEvent extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'title', 'description', 'event_date', 'event_time',
        'venue', 'max_attendees', 'registration_fee', 'organizer',
        'status',
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime',
        'max_attendees' => 'integer',
        'registration_fee' => 'decimal:2',
    ];

    public function attendees()
    {
        return $this->belongsToMany(Alumni::class, 'alumni_event_attendees')->withPivot('attended', 'remarks')->withTimestamps();
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now());
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('title', 'like', "%{$term}%");
    }
}
