<?php

namespace App\Models\Asset;

use App\Models\Hr\Employee;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class AssetAllocation extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'asset_id', 'employee_id', 'allocated_date',
        'expected_return_date', 'return_date', 'condition_on_alloc',
        'condition_on_return', 'purpose', 'status', 'remarks',
    ];

    protected $casts = [
        'allocated_date' => 'date',
        'expected_return_date' => 'date',
        'return_date' => 'date',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'allocated');
    }
}
