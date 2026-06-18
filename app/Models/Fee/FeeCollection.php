<?php

namespace App\Models\Fee;

use App\Models\Student\Student;
use App\Models\Accounting\ChartOfAccount;
use App\Models\Fee\FeeCategory;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class FeeCollection extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'student_id', 'fee_structure_id', 'fee_installment_id',
        'receipt_no', 'amount', 'discount_amount', 'late_fee_amount',
        'total_amount', 'paid_amount', 'balance_amount', 'payment_date',
        'payment_mode', 'transaction_id', 'chart_of_account_id',
        'cheque_no', 'cheque_date', 'bank_name', 'remarks', 'status',
    ];
    protected $casts = [
        'amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'late_fee_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'payment_date' => 'date',
        'cheque_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class);
    }

    public function feeCategory()
    {
        return $this->belongsTo(FeeCategory::class);
    }

    public function feeInstallment()
    {
        return $this->belongsTo(FeeInstallment::class);
    }

    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class);
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('receipt_no', 'like', "%{$search}%");
    }
}
