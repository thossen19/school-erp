<?php

namespace App\Models\Admission;

use App\Models\Academic\ClassModel;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class EntranceExam extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'branch_id', 'academic_year_id', 'title', 'class_id',
        'exam_date', 'duration', 'total_marks', 'passing_marks',
        'description', 'status', 'start_time',
    ];
    protected $casts = [
        'exam_date' => 'date',
        'duration' => 'integer',
        'total_marks' => 'integer',
        'passing_marks' => 'integer',
        'status' => 'boolean',
    ];

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function results()
    {
        return $this->hasMany(EntranceExamResult::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('title', 'like', "%{$search}%");
    }
}
