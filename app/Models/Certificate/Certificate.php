<?php

namespace App\Models\Certificate;

use App\Models\Student\Student;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Certificate extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'student_id', 'certificate_type', 'template_id',
        'certificate_number', 'issue_date', 'status',
    ];

    protected $casts = [
        'issue_date' => 'date',
    ];

    public function template()
    {
        return $this->belongsTo(CertificateTemplate::class, 'template_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('certificate_number', 'like', "%{$search}%");
    }
}
