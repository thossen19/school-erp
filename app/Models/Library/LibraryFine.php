<?php

namespace App\Models\Library;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class LibraryFine extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'book_issue_id', 'library_member_id',
        'fine_amount', 'fine_date', 'reason', 'is_paid',
        'payment_date', 'remarks',
    ];

    protected $casts = [
        'fine_amount' => 'decimal:2',
        'fine_date' => 'date',
        'is_paid' => 'boolean',
        'payment_date' => 'date',
    ];

    public function bookIssue()
    {
        return $this->belongsTo(BookIssue::class);
    }

    public function libraryMember()
    {
        return $this->belongsTo(LibraryMember::class);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('is_paid', false);
    }
}
