<?php

namespace App\Models\Student;

use App\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class StudentAward extends Model
{
    use HasFactory, AuditableTrait;

    protected $fillable = [
        'student_id', 'award_name', 'award_type', 'date',
        'description', 'certificate', 'date_awarded', 'awarded_by',
    ];

    protected $casts = [
        'date' => 'date',
        'date_awarded' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('award_name', 'like', "%{$search}%");
    }
}
