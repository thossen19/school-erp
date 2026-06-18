<?php

namespace App\Models\Academic;

use App\Models\Student\Student;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ClassModel extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $table = 'classes';

    protected $fillable = [
        'school_id', 'name', 'numeric_value', 'code', 'description',
        'capacity', 'status',
    ];
    protected $casts = [
        'numeric_value' => 'integer',
        'capacity' => 'integer',
        'status' => 'boolean',
    ];

    public function sections()
    {
        return $this->hasMany(Section::class, 'class_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subjects', 'class_id', 'subject_id')->withPivot('is_compulsory', 'max_students_per_week')->withTimestamps();
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
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
