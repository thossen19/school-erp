<?php

namespace App\Models\Mis;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ScheduledReport extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'report_template_id', 'name', 'frequency',
        'recipients', 'parameters', 'last_run_at', 'next_run_at',
        'format', 'is_active',
    ];

    protected $casts = [
        'recipients' => 'array',
        'parameters' => 'array',
        'last_run_at' => 'datetime',
        'next_run_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function reportTemplate()
    {
        return $this->belongsTo(ReportTemplate::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'like', "%{$term}%");
    }
}
