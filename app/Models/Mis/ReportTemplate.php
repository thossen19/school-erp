<?php

namespace App\Models\Mis;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ReportTemplate extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'name', 'code', 'description', 'category',
        'type', 'config', 'parameters', 'is_system', 'status',
    ];

    protected $casts = [
        'config' => 'array',
        'parameters' => 'array',
        'is_system' => 'boolean',
        'status' => 'boolean',
    ];

    public function scheduledReports()
    {
        return $this->hasMany(ScheduledReport::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'like', "%{$term}%");
    }
}
