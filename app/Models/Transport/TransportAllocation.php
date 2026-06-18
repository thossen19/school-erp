<?php

namespace App\Models\Transport;

use App\Models\Student\Student;
use App\Models\Academic\AcademicYear;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class TransportAllocation extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'student_id', 'transport_route_id',
        'transport_route_stop_id', 'academic_year_id', 'allocation_date',
        'trip_type', 'fee_amount', 'status',
    ];

    protected $casts = [
        'allocation_date' => 'date',
        'fee_amount' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function route()
    {
        return $this->belongsTo(TransportRoute::class, 'transport_route_id');
    }

    public function stop()
    {
        return $this->belongsTo(TransportRouteStop::class, 'transport_route_stop_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
