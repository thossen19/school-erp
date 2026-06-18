<?php

namespace App\Models\Assessment;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class GradeRange extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'grading_system_id', 'grade', 'min_percentage',
        'max_percentage', 'grade_point', 'description',
    ];
    protected $casts = [
        'min_percentage' => 'float',
        'max_percentage' => 'float',
        'grade_point' => 'float',
    ];

    public function gradingSystem()
    {
        return $this->belongsTo(GradingSystem::class);
    }
}
