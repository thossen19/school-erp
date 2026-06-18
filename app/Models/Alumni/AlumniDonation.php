<?php

namespace App\Models\Alumni;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class AlumniDonation extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'alumni_id', 'donation_date', 'amount',
        'payment_mode', 'transaction_id', 'purpose', 'is_anonymous',
        'remarks', 'status',
    ];

    protected $casts = [
        'donation_date' => 'date',
        'amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
    ];

    public function alumni()
    {
        return $this->belongsTo(Alumni::class);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('purpose', 'like', "%{$term}%");
    }
}
