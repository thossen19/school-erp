<?php

namespace App\Models\Attendance;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class AttendanceSetting extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'academic_year_id', 'attendance_type', 'days_per_week',
        'working_days', 'mark_after_time', 'half_day_after_time',
        'late_mark_enabled', 'auto_approve_leave', 'grace_period_minutes',
        'config', 'status',
    ];
    protected $casts = [
        'days_per_week' => 'integer',
        'working_days' => 'integer',
        'mark_after_time' => 'datetime',
        'half_day_after_time' => 'datetime',
        'late_mark_enabled' => 'boolean',
        'auto_approve_leave' => 'boolean',
        'grace_period_minutes' => 'integer',
        'config' => 'array',
        'status' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
