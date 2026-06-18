<?php

namespace App\Models\Fee;

use App\Models\Student\Student;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class FeeDueTracking extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'student_id', 'fee_structure_id', 'total_due',
        'paid_amount', 'balance_due', 'due_date', 'last_reminder_date',
        'reminder_count', 'penalty_applied', 'penalty_amount', 'status',
    ];
    protected $casts = [
        'total_due' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_due' => 'decimal:2',
        'due_date' => 'date',
        'last_reminder_date' => 'datetime',
        'reminder_count' => 'integer',
        'penalty_applied' => 'boolean',
        'penalty_amount' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class);
    }

    public function scopeOverdue($query)
    {
        return $query->where('balance_due', '>', 0)->where('status', 'overdue');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('status', 'like', "%{$search}%");
    }
}
