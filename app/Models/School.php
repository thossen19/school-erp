<?php

namespace App\Models;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class School extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'name', 'code', 'email', 'phone', 'address', 'city', 'state', 'country',
        'pincode', 'logo', 'website', 'status', 'config', 'features',
    ];
    protected $casts = [
        'config' => 'array',
        'features' => 'array',
        'status' => 'boolean',
    ];

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function academicYears()
    {
        return $this->hasMany(AcademicYear::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student\Student::class);
    }

    public function employees()
    {
        return $this->hasMany(Hr\Employee::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $this->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
        });
    }
}
