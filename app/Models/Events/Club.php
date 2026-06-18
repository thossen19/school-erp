<?php

namespace App\Models\Events;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Club extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'name', 'code', 'description', 'club_type',
        'advisor_employee_id', 'max_members', 'meeting_schedule',
        'status',
    ];
    protected $casts = [
        'max_members' => 'integer',
        'meeting_schedule' => 'array',
        'status' => 'boolean',
    ];

    public function advisor()
    {
        return $this->belongsTo(Hr\Employee::class, 'advisor_employee_id');
    }

    public function members()
    {
        return $this->hasMany(ClubMember::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }
}
