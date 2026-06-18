<?php

namespace App\Models\FrontOffice;

use App\Models\Student\Student;
use App\Models\Hr\Employee;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Enquiry extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'name', 'email', 'phone', 'address',
        'enquiry_type', 'source', 'description', 'assigned_to',
        'follow_up_date', 'next_follow_up', 'status', 'remarks',
    ];

    protected $casts = [
        'follow_up_date' => 'datetime',
        'next_follow_up' => 'datetime',
    ];

    public function assignedTo()
    {
        return $this->belongsTo(Employee::class, 'assigned_to');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%")->orWhere('phone', 'like', "%{$term}%");
        });
    }
}
