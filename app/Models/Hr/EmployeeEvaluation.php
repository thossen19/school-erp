<?php

namespace App\Models\Hr;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class EmployeeEvaluation extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'employee_id', 'evaluator_id', 'evaluation_date',
        'evaluation_type', 'rating', 'criteria', 'comments',
        'next_evaluation_date', 'status',
    ];
    protected $casts = [
        'evaluation_date' => 'date',
        'rating' => 'float',
        'criteria' => 'array',
        'next_evaluation_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(Employee::class, 'evaluator_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('evaluation_type', 'like', "%{$search}%");
    }
}
