<?php

namespace App\Models\Student;

use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class StudentHouse extends Model
{
    use HasFactory, SchoolScopeTrait;

    protected $fillable = [
        'school_id', 'name', 'code', 'color', 'motto', 'description',
        'captain_user_id', 'vice_captain_user_id', 'status',
    ];
    protected $casts = [
        'status' => 'boolean',
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'house_id');
    }

    public function captain()
    {
        return $this->belongsTo(User::class, 'captain_user_id');
    }

    public function viceCaptain()
    {
        return $this->belongsTo(User::class, 'vice_captain_user_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $this->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%");
        });
    }
}
