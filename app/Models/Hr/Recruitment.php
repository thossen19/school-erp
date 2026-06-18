<?php

namespace App\Models\Hr;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Recruitment extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'department_id', 'designation_id', 'job_title',
        'vacancies', 'description', 'requirements', 'salary_range',
        'posted_date', 'closing_date', 'status',
    ];

    protected $casts = [
        'vacancies' => 'integer',
        'requirements' => 'array',
        'posted_date' => 'date',
        'closing_date' => 'date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('job_title', 'like', "%{$search}%");
    }
}
