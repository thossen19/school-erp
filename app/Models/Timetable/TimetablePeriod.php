<?php

namespace App\Models\Timetable;

use App\Models\Academic\Subject;
use App\Models\Hr\Employee;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class TimetablePeriod extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'timetable_id', 'subject_id', 'employee_id',
        'day_of_week', 'start_time', 'end_time', 'room_number',
        'period_number', 'is_break', 'label',
    ];
    protected $casts = [
        'day_of_week' => 'integer',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'period_number' => 'integer',
        'is_break' => 'boolean',
    ];

    public function timetable()
    {
        return $this->belongsTo(Timetable::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('label', 'like', "%{$search}%");
    }
}
