<?php

namespace App\Models\Accounting;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class BankReconciliation extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'chart_of_account_id', 'reconciliation_date',
        'bank_balance', 'book_balance', 'difference',
        'outstanding_checks', 'deposits_in_transit',
        'bank_charges', 'interest_earned', 'adjustments',
        'is_balanced', 'notes', 'status',
    ];

    protected $casts = [
        'reconciliation_date' => 'date',
        'bank_balance' => 'decimal:2',
        'book_balance' => 'decimal:2',
        'difference' => 'decimal:2',
        'outstanding_checks' => 'array',
        'deposits_in_transit' => 'array',
        'bank_charges' => 'decimal:2',
        'interest_earned' => 'decimal:2',
        'adjustments' => 'array',
        'is_balanced' => 'boolean',
    ];

    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class);
    }
}
