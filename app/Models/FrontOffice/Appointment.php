<?php

namespace App\Models\FrontOffice;

use App\Models\Hr\Employee;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Appointment extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'visitor_name', 'visitor_email', 'visitor_phone',
        'purpose', 'employee_id', 'appointment_date', 'start_time',
        'end_time', 'status', 'notes',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('visitor_name', 'like', "%{$term}%")->orWhere('visitor_phone', 'like', "%{$term}%");
        });
    }
}
