<?php

namespace App\Models\Student;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class StudentDocument extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'student_id', 'document_type', 'document_name',
        'file_path', 'file_size', 'mime_type', 'is_verified', 'verified_at',
        'verified_by', 'expiry_date', 'remarks',
    ];
    protected $casts = [
        'file_size' => 'integer',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'expiry_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('document_name', 'like', "%{$search}%");
    }
}
