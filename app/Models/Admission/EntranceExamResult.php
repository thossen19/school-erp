<?php

namespace App\Models\Admission;

use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class EntranceExamResult extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'entrance_exam_id', 'admission_form_id', 'marks_obtained',
        'total_marks', 'percentage', 'rank', 'result', 'remarks',
    ];
    protected $casts = [
        'marks_obtained' => 'float',
        'total_marks' => 'float',
        'percentage' => 'float',
        'rank' => 'integer',
    ];

    public function entranceExam()
    {
        return $this->belongsTo(EntranceExam::class);
    }

    public function admissionForm()
    {
        return $this->belongsTo(AdmissionForm::class);
    }

    public function scopePassed($query)
    {
        return $query->where('result', 'passed');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $this->where('remarks', 'like', "%{$search}%");
        });
    }
}
