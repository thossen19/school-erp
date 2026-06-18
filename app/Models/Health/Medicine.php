<?php

namespace App\Models\Health;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Medicine extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $table = 'medicines';

    protected $fillable = [
        'school_id', 'student_id', 'medicine_name', 'dosage', 'frequency',
        'start_date', 'end_date', 'prescribed_by', 'remarks',
        'strength', 'reorder_level', 'requires_prescription',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(\App\Models\Student\Student::class);
    }

    public function scopeActive($query)
    {
        return $query->where(function($q) {
            $q->whereNull('end_date')->orWhere('end_date', '>=', now());
        });
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('medicine_name', 'like', "%{$term}%");
        });
    }
}
