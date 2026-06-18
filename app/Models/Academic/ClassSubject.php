<?php

namespace App\Models\Academic;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ClassSubject extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'class_id', 'subject_id', 'is_compulsory',
        'max_periods_per_week', 'total_marks',
    ];
    protected $casts = [
        'is_compulsory' => 'boolean',
        'max_periods_per_week' => 'integer',
        'total_marks' => 'integer',
    ];

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
