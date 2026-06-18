<?php

namespace App\Models\Alumni;

use App\Models\Student\Student;
use App\Traits\AuditableTrait;
use App\Traits\SchoolScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Alumni extends Model
{
    use HasFactory, SchoolScopeTrait, AuditableTrait;

    protected $fillable = [
        'school_id', 'student_id', 'first_name', 'last_name',
        'email', 'phone', 'graduation_year', 'current_occupation',
        'current_company', 'current_position', 'address', 'city',
        'state', 'country', 'linkedin_url', 'facebook_url',
        'is_newsletter_subscribed', 'status',
    ];

    protected $casts = [
        'graduation_year' => 'integer',
        'is_newsletter_subscribed' => 'boolean',
    ];

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function events()
    {
        return $this->belongsToMany(AlumniEvent::class, 'alumni_event_attendees')->withPivot('attended', 'remarks')->withTimestamps();
    }

    public function donations()
    {
        return $this->hasMany(AlumniDonation::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('first_name', 'like', "%{$term}%")
              ->orWhere('last_name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });
    }
}
