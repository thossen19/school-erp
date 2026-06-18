<?php

namespace App\Models\Fee;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Scholarship extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'name', 'code', 'description', 'type',
        'amount', 'is_percentage', 'max_amount', 'criteria',
        'total_slots', 'available_slots', 'application_start', 'application_end',
        'status',
    ];
    protected $casts = [
        'amount' => 'decimal:2',
        'is_percentage' => 'boolean',
        'max_amount' => 'decimal:2',
        'criteria' => 'array',
        'total_slots' => 'integer',
        'available_slots' => 'integer',
        'application_start' => 'date',
        'application_end' => 'date',
        'status' => 'boolean',
    ];

    public function students()
    {
        return $this->hasMany(ScholarshipStudent::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $this->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%");
        });
    }
}
