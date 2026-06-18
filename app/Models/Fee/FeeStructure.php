<?php

namespace App\Models\Fee;

use App\Models\Academic\ClassModel;
use App\Models\Academic\AcademicYear;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class FeeStructure extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'fee_category_id', 'name', 'class_id', 'academic_year_id',
        'amount', 'is_installment', 'installment_count', 'due_date',
        'late_fee_amount', 'late_fee_frequency', 'description', 'status',
    ];
    protected $casts = [
        'amount' => 'decimal:2',
        'is_installment' => 'boolean',
        'installment_count' => 'integer',
        'due_date' => 'date',
        'late_fee_amount' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function feeCategory()
    {
        return $this->belongsTo(FeeCategory::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function installments()
    {
        return $this->hasMany(FeeInstallment::class);
    }

    public function discounts()
    {
        return $this->hasMany(FeeDiscount::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }
}
