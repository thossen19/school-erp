<?php

namespace App\Models\Timetable;

use App\Models\Hr\Employee;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class SubstitutionRequest extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'timetable_period_id', 'original_teacher_id',
        'substitute_teacher_id', 'date', 'reason', 'status', 'substitute_employee_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function timetablePeriod()
    {
        return $this->belongsTo(TimetablePeriod::class);
    }

    public function originalTeacher()
    {
        return $this->belongsTo(\App\Models\User::class, 'original_teacher_id');
    }

    public function substituteTeacher()
    {
        return $this->belongsTo(\App\Models\User::class, 'substitute_teacher_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('reason', 'like', "%{$search}%");
    }
}
