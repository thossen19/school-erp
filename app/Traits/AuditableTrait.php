<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait AuditableTrait
{
    public static function bootAuditableTrait(): void
    {
        static::creating(function (Model $model) {
            $model->{$model->getCreatedByColumn()} = $model->getAuditUser();
        });

        static::updating(function (Model $model) {
            $model->{$model->getUpdatedByColumn()} = $model->getAuditUser();
        });

        if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses_recursive(static::class))) {
            static::deleting(function (Model $model) {
                if (!$model->isForceDeleting()) {
                    $model->{$model->getDeletedByColumn()} = $model->getAuditUser();
                    $model->save();
                }
            });
        }
    }

    public function scopeCreatedByUser(Builder $query, $userId): Builder
    {
        return $query->where($this->getCreatedByColumn(), $userId);
    }

    public function scopeUpdatedByUser(Builder $query, $userId): Builder
    {
        return $query->where($this->getUpdatedByColumn(), $userId);
    }

    public function creator()
    {
        return $this->belongsTo(config('auth.providers.users.model', 'App\Models\User'), $this->getCreatedByColumn());
    }

    public function editor()
    {
        return $this->belongsTo(config('auth.providers.users.model', 'App\Models\User'), $this->getUpdatedByColumn());
    }

    protected function getCreatedByColumn(): string
    {
        return property_exists($this, 'createdByColumn') ? $this->createdByColumn : 'created_by';
    }

    protected function getUpdatedByColumn(): string
    {
        return property_exists($this, 'updatedByColumn') ? $this->updatedByColumn : 'updated_by';
    }

    protected function getDeletedByColumn(): string
    {
        return property_exists($this, 'deletedByColumn') ? $this->deletedByColumn : 'deleted_by';
    }

    protected function getAuditUser(): ?int
    {
        return Auth::check() ? Auth::id() : null;
    }
}
