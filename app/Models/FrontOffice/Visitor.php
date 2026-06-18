<?php

namespace App\Models\FrontOffice;

use App\Models\Student\Student;
use App\Models\Hr\Employee;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Visitor extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'name', 'email', 'phone', 'organization',
        'visitor_type', 'purpose', 'person_to_meet', 'check_in',
        'check_out', 'id_proof_type', 'id_proof_number',
        'vehicle_number', 'visitor_count', 'remarks', 'status',
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'visitor_count' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'checked_in');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")->orWhere('phone', 'like', "%{$term}%")->orWhere('organization', 'like', "%{$term}%");
        });
    }
}
