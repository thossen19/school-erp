<?php

namespace App\Models\Alumni;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class JobPost extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'alumni_id', 'company_name', 'job_title',
        'description', 'requirements', 'location', 'employment_type',
        'salary_range_min', 'salary_range_max', 'application_url',
        'application_email', 'posted_date', 'closing_date', 'status',
    ];

    protected $casts = [
        'requirements' => 'array',
        'salary_range_min' => 'decimal:2',
        'salary_range_max' => 'decimal:2',
        'posted_date' => 'date',
        'closing_date' => 'date',
    ];

    public function alumni()
    {
        return $this->belongsTo(Alumni::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('job_title', 'like', "%{$term}%")->orWhere('company_name', 'like', "%{$term}%");
        });
    }
}
