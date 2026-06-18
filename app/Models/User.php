<?php

namespace App\Models;

use App\Traits\SchoolScopeTrait;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasApiTokens, Notifiable, SchoolScopeTrait, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'school_id', 'branch_id', 'username',
        'phone', 'avatar', 'user_type', 'status', 'locale', 'theme_preference',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => 'boolean',
        'theme_preference' => 'string',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeByUserType($query, $type)
    {
        return $query->where('user_type', $type);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('username', 'like', "%{$search}%");
        });
    }
}
