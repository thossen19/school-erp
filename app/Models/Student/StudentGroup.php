<?php

namespace App\Models\Student;

use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class StudentGroup extends Model
{
    use HasFactory, SchoolScopeTrait;

    protected $fillable = [
        'school_id', 'name', 'code', 'description', 'status',
    ];
    protected $casts = [
        'status' => 'boolean',
    ];

    public function members()
    {
        return $this->hasMany(StudentGroupMember::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_group_members')->withPivot('role', 'joined_at')->withTimestamps();
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
