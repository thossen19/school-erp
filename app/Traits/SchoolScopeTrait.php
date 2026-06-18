<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait SchoolScopeTrait
{
    protected string $schoolIdColumn = 'school_id';

    public static function bootSchoolScopeTrait(): void
    {
        static::addGlobalScope('school_scope', function (Builder $builder) {
            if (!Auth::hasUser()) {
                return;
            }
            $schoolId = static::getSchoolId();
            if ($schoolId !== null) {
                $builder->where((new static)->getSchoolIdColumn(), $schoolId);
            }
        });

        static::creating(function ($model) {
            $schoolId = static::getSchoolId();
            $column = $model->getSchoolIdColumn();
            if ($schoolId !== null && empty($model->{$column})) {
                $model->{$column} = $schoolId;
            }
        });
    }

    public function scopeBySchool(Builder $query, $schoolId): Builder
    {
        return $query->where($this->getSchoolIdColumn(), $schoolId);
    }

    public function scopeWithoutSchoolScope(Builder $query): Builder
    {
        return $query->withoutGlobalScope('school_scope');
    }

    public function setSchoolIdColumn(string $column): static
    {
        $this->schoolIdColumn = $column;

        return $this;
    }

    public function getSchoolIdColumn(): string
    {
        return property_exists($this, 'schoolIdColumn') ? $this->schoolIdColumn : 'school_id';
    }

    protected static function getSchoolId(): ?int
    {
        if (Auth::hasUser()) {
            $user = Auth::user();
            if (isset($user->school_id)) {
                return (int) $user->school_id;
            }
        }

        if (session()->has('school_id')) {
            return (int) session('school_id');
        }

        if (request()->has('school_id')) {
            return (int) request('school_id');
        }

        return null;
    }
}
