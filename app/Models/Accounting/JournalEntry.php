<?php

namespace App\Models\Accounting;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class JournalEntry extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'entry_no', 'entry_date', 'description',
        'reference_type', 'reference_id', 'total_debit', 'total_credit',
        'is_approved', 'approved_by', 'approved_at', 'status',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'total_debit' => 'decimal:2',
        'total_credit' => 'decimal:2',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(JournalEntryItem::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('entry_no', 'like', "%{$term}%");
    }
}
