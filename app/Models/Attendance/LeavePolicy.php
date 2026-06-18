<?php

namespace App\Models\Attendance;

use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeavePolicy extends Model
{
    use HasFactory, SchoolScopeTrait;

    protected $fillable = [
        'school_id', 'name', 'code', 'description', 'max_days_per_year',
        'max_consecutive_days', 'requires_approval', 'is_paid',
        'is_active', 'applicable_roles', 'terms',
    ];

    protected $casts = [
        'max_days_per_year' => 'integer',
        'max_consecutive_days' => 'integer',
        'requires_approval' => 'boolean',
        'is_paid' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
