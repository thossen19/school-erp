<?php

namespace App\Models\Fee;

use App\Models\Student\Student;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class FeeConcession extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'student_id', 'fee_structure_id', 'fee_discount_id',
        'concession_percentage', 'concession_amount', 'reason',
        'approved_by', 'approved_at', 'valid_from', 'valid_until', 'status',
    ];
    protected $casts = [
        'concession_percentage' => 'float',
        'concession_amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class);
    }

    public function feeDiscount()
    {
        return $this->belongsTo(FeeDiscount::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
