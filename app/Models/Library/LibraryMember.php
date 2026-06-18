<?php

namespace App\Models\Library;

use App\Models\Student\Student;
use App\Models\Hr\Employee;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class LibraryMember extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'student_id', 'employee_id', 'membership_no',
        'membership_type', 'joining_date', 'valid_until',
        'max_books_allowed', 'is_active',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'valid_until' => 'date',
        'max_books_allowed' => 'integer',
        'is_active' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function issues()
    {
        return $this->hasMany(BookIssue::class);
    }

    public function fines()
    {
        return $this->hasMany(LibraryFine::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('membership_no', 'like', "%{$term}%");
    }
}
