<?php

namespace App\Models\Student;

use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class StudentTimeline extends Model
{
    use HasFactory, SchoolScopeTrait;

    protected $fillable = [
        'school_id', 'student_id', 'title', 'description', 'timeline_date', 'date',
        'type', 'is_visible_to_student', 'status',
    ];
    protected $casts = [
        'timeline_date' => 'datetime',
        'is_visible_to_student' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('title', 'like', "%{$search}%");
    }
}
