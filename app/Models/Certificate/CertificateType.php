<?php

namespace App\Models\Certificate;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class CertificateType extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'name', 'code', 'description',
        'has_qr', 'has_digital_signature', 'fee',
    ];

    protected $casts = [
        'has_qr' => 'boolean',
        'has_digital_signature' => 'boolean',
        'fee' => 'decimal:2',
    ];

    public function templates()
    {
        return $this->hasMany(CertificateTemplate::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function scopeActive($query)
    {
        return $query->where('has_qr', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%");
        });
    }
}
