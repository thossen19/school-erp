<?php

namespace App\Models\Attendance;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Holiday extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'name', 'date', 'type', 'description',
        'is_recurring_annually', 'status',
    ];
    protected $casts = [
        'date' => 'date',
        'is_recurring_annually' => 'boolean',
        'status' => 'boolean',
    ];

    public function calendars()
    {
        return $this->belongsToMany(HolidayCalendar::class, 'holiday_calendar_holiday')->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeByYear($query, $year)
    {
        return $query->whereYear('date', $year);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }
}
