<?php

namespace App\Models\Admission;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class MeritList extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'name', 'academic_year_id', 'class_id', 'entrance_exam_id',
        'generated_date', 'rank_from', 'rank_to', 'criteria', 'status',
    ];
    protected $casts = [
        'generated_date' => 'date',
        'rank_from' => 'integer',
        'rank_to' => 'integer',
        'criteria' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }
}
