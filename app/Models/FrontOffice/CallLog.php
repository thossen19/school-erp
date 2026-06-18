<?php

namespace App\Models\FrontOffice;

use App\Models\Hr\Employee;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class CallLog extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'caller_name', 'caller_phone', 'caller_email',
        'call_type', 'purpose', 'duration_minutes', 'call_date',
        'received_by', 'call_notes', 'follow_up_required',
        'follow_up_date', 'status',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'call_date' => 'datetime',
        'follow_up_required' => 'boolean',
        'follow_up_date' => 'datetime',
    ];

    public function receivedBy()
    {
        return $this->belongsTo(Employee::class, 'received_by');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('caller_name', 'like', "%{$term}%")->orWhere('caller_phone', 'like', "%{$term}%");
        });
    }
}
