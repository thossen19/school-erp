<?php

namespace App\Models\Hr;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class JobApplication extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'recruitment_id', 'applicant_name', 'email', 'phone',
        'resume_path', 'cover_letter', 'qualification', 'experience_years',
        'current_company', 'current_position', 'expected_salary',
        'application_date', 'status', 'remarks',
    ];
    protected $casts = [
        'experience_years' => 'integer',
        'expected_salary' => 'decimal:2',
        'application_date' => 'date',
    ];

    public function recruitment()
    {
        return $this->belongsTo(Recruitment::class);
    }

    public function scopeShortlisted($query)
    {
        return $query->where('status', 'shortlisted');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $this->where('applicant_name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
        });
    }
}
