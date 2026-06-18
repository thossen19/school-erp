<?php

namespace App\Models\Academic;

use App\Models\Student\Student;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Section extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'class_id', 'name', 'code', 'capacity', 'status',
    ];
    protected $casts = [
        'capacity' => 'integer',
        'status' => 'boolean',
    ];

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'section_id');
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
