<?php

namespace App\Models\Library;

use App\Models\User;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class BookIssue extends Model
{
    use HasFactory, SchoolScopeTrait;

    protected $fillable = [
        'school_id', 'book_id', 'member_id', 'issue_date',
        'due_date', 'return_date', 'status', 'issued_by',
        'fine_amount', 'fine_paid', 'remarks',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function libraryMember()
    {
        return $this->belongsTo(LibraryMember::class, 'member_id');
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function fines()
    {
        return $this->hasMany(LibraryFine::class, 'book_issue_id');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())->where('status', '!=', 'returned');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('remarks', 'like', "%{$term}%");
    }
}
