<?php

namespace App\Models\Alumni;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class AlumniEventAttendee extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $table = 'alumni_event_attendees';

    protected $fillable = [
        'school_id', 'alumni_event_id', 'alumni_id', 'attended', 'remarks',
    ];

    protected $casts = [
        'attended' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(AlumniEvent::class, 'alumni_event_id');
    }

    public function alumni()
    {
        return $this->belongsTo(Alumni::class);
    }
}
