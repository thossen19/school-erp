<?php

namespace App\Models\Attendance;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class HolidayCalendar extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'name', 'academic_year_id', 'description', 'is_active',
    ];
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function holidays()
    {
        return $this->belongsToMany(Holiday::class, 'holiday_calendar_holiday')->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }
}
