<?php

namespace App\Models\Events;

use App\Models\Student\Student;
use App\Models\Hr\Employee;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class EventRegistration extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'event_id', 'student_id', 'employee_id',
        'registration_date', 'payment_status', 'amount_paid',
        'attended', 'remarks',
    ];
    protected $casts = [
        'registration_date' => 'datetime',
        'amount_paid' => 'decimal:2',
        'attended' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
