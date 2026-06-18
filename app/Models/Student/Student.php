<?php

namespace App\Models\Student;

use App\Models\Student\Guardian;
use App\Models\Academic\ClassModel;
use App\Models\Academic\Section;
use App\Models\Academic\AcademicYear;
use App\Models\School;
use App\Models\Branch;
use App\Models\Attendance\Attendance;
use App\Models\Fee\FeeCollection;
use App\Models\User;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Student extends Model
{
    use HasFactory, SchoolScopeTrait;

    protected $fillable = [
        'school_id', 'branch_id', 'user_id', 'admission_no', 'roll_number',
        'first_name', 'last_name', 'date_of_birth', 'gender', 'blood_group',
        'nationality', 'religion', 'caste_category', 'mother_tongue',
        'admission_date', 'class_id', 'section_id', 'academic_year_id',
        'house_id', 'group_id', 'status', 'remarks',
        'phone', 'email', 'photo', 'address', 'city', 'state', 'country', 'pincode',
    ];
    protected $casts = [
        'date_of_birth' => 'date',
        'admission_date' => 'date',
    ];

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function house()
    {
        return $this->belongsTo(StudentHouse::class, 'house_id');
    }

    public function parents()
    {
        return $this->belongsToMany(Guardian::class, 'student_parents', 'student_id', 'parent_id')->withPivot('relationship', 'is_emergency_contact')->withTimestamps();
    }

    public function medicalRecords()
    {
        return $this->hasMany(StudentMedicalRecord::class);
    }

    public function academicHistories()
    {
        return $this->hasMany(StudentAcademicHistory::class);
    }

    public function documents()
    {
        return $this->hasMany(StudentDocument::class);
    }

    public function disciplines()
    {
        return $this->hasMany(StudentDiscipline::class);
    }

    public function awards()
    {
        return $this->hasMany(StudentAward::class);
    }

    public function transfers()
    {
        return $this->hasMany(StudentTransfer::class);
    }

    public function promotions()
    {
        return $this->hasMany(StudentPromotion::class);
    }

    public function timeline()
    {
        return $this->hasOne(StudentTimeline::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function feeCollections()
    {
        return $this->hasMany(FeeCollection::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('admission_no', 'like', "%{$search}%")
              ->orWhere('roll_number', 'like', "%{$search}%");
        });
    }
}
