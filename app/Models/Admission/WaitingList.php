<?php

namespace App\Models\Admission;

use App\Models\Academic\ClassModel;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class WaitingList extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'admission_form_id', 'class_id', 'academic_year_id',
        'rank', 'status', 'waitlisted_date', 'remarks',
    ];
    protected $casts = [
        'rank' => 'integer',
        'waitlisted_date' => 'date',
    ];

    public function admissionForm()
    {
        return $this->belongsTo(AdmissionForm::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'waitlisted');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $this->where('remarks', 'like', "%{$search}%");
        });
    }
}
