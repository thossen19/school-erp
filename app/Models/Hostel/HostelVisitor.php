<?php

namespace App\Models\Hostel;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class HostelVisitor extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'hostel_id', 'visitor_name', 'visitor_phone',
        'visitor_email', 'student_name', 'student_phone',
        'relationship', 'purpose', 'check_in', 'check_out',
        'id_proof_type', 'id_proof_number', 'remarks', 'status',
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }

    public function scopeCheckedIn($query)
    {
        return $query->where('status', 'checked_in');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('visitor_name', 'like', "%{$term}%")->orWhere('visitor_phone', 'like', "%{$term}%");
        });
    }
}
