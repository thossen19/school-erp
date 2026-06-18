<?php

namespace App\Models\Hr;

use App\Models\School;
use App\Models\User;
use App\Models\Payroll\SalaryStructure;
use App\Models\Payroll\Payroll;
use App\Models\Payroll\LoanRequest;
use App\Models\Payroll\OvertimeRecord;
use App\Models\Attendance\LeaveRequest;
use App\Models\Attendance\LeaveBalance;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Employee extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'user_id', 'department_id', 'designation_id',
        'employee_no', 'first_name', 'last_name', 'email', 'phone',
        'date_of_birth', 'date_of_joining', 'date_of_leaving',
        'gender', 'marital_status', 'blood_group', 'nationality',
        'address', 'city', 'state', 'country', 'pincode',
        'qualification', 'experience_years', 'employment_type',
        'work_shift', 'biometric_id', 'status', 'remarks',
    ];
    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_joining' => 'date',
        'date_of_leaving' => 'date',
        'experience_years' => 'integer',
    ];

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function contracts()
    {
        return $this->hasMany(EmployeeContract::class);
    }

    public function evaluations()
    {
        return $this->hasMany(EmployeeEvaluation::class);
    }

    public function salaryStructure()
    {
        return $this->hasOne(SalaryStructure::class);
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function loanRequests()
    {
        return $this->hasMany(LoanRequest::class);
    }

    public function overtimeRecords()
    {
        return $this->hasMany(OvertimeRecord::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%")->orWhere('employee_no', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
        });
    }
}
