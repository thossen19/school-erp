<?php

namespace App\Models\Timetable;

use App\Models\Academic\Subject;
use App\Models\Hr\Employee;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class TimetableAllocation extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'timetable_id', 'timetable_period_id', 'employee_id',
        'subject_id', 'allocation_date', 'notes',
    ];
    protected $casts = [
        'allocation_date' => 'date',
    ];

    public function timetable()
    {
        return $this->belongsTo(Timetable::class);
    }

    public function timetablePeriod()
    {
        return $this->belongsTo(TimetablePeriod::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
