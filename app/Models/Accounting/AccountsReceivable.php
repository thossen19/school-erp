<?php

namespace App\Models\Accounting;

use App\Models\Student\Student;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class AccountsReceivable extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'student_id', 'invoice_no', 'invoice_date',
        'invoice_amount', 'paid_amount', 'balance_due',
        'due_date', 'fee_collection_id', 'notes', 'status',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'invoice_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_due' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())->where('status', 'unpaid');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('invoice_no', 'like', "%{$term}%");
    }
}
