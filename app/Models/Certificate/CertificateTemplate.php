<?php

namespace App\Models\Certificate;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class CertificateTemplate extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'name', 'type', 'content', 'layout',
        'orientation', 'variables', 'is_default', 'status', 'margin_left',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'status' => 'boolean',
        'variables' => 'array',
    ];

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'template_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }
}
