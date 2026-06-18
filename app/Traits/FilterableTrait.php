<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait FilterableTrait
{
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        foreach ($filters as $field => $value) {
            if ($value === '' || $value === null) {
                continue;
            }

            $method = $this->getFilterMethod($field);

            if (method_exists($this, $method)) {
                $this->{$method}($query, $field, $value);
            } elseif ($this->isDateField($field, $value)) {
                $this->applyDateFilter($query, $field, $value);
            } elseif (is_array($value)) {
                $query->whereIn($field, $value);
            } else {
                $query->where($field, $value);
            }
        }

        return $query;
    }

    public function scopeSearch(Builder $query, string $search, array $fields = []): Builder
    {
        $fields = !empty($fields) ? $fields : $this->getSearchableFields();

        if (empty($fields) || empty($search)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search, $fields) {
            $first = true;
            foreach ($fields as $field) {
                if ($first) {
                    $q->where($field, 'like', "%{$search}%");
                    $first = false;
                } else {
                    $q->orWhere($field, 'like', "%{$search}%");
                }
            }
        });
    }

    public function scopeSort(Builder $query, string $column = null, string $direction = 'asc'): Builder
    {
        $column = $column ?? $this->getSortColumn();
        $direction = in_array(strtolower($direction), ['asc', 'desc']) ? $direction : 'asc';

        if (in_array($column, $this->getSortableFields())) {
            $query->orderBy($column, $direction);
        }

        return $query;
    }

    public function scopeDateRange(Builder $query, string $column, string $startDate = null, string $endDate = null): Builder
    {
        if ($startDate) {
            $query->whereDate($column, '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate($column, '<=', $endDate);
        }

        return $query;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('status', 'inactive');
    }

    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeWhereLike(Builder $query, string $column, string $value): Builder
    {
        return $query->where($column, 'like', "%{$value}%");
    }

    public function scopeOrWhereLike(Builder $query, string $column, string $value): Builder
    {
        return $query->orWhere($column, 'like', "%{$value}%");
    }

    public function scopeWhereBetweenDates(Builder $query, string $column, string $start, string $end): Builder
    {
        return $query->whereBetween($column, [$start, $end]);
    }

    protected function getFilterMethod(string $field): string
    {
        $parts = explode('_', $field);
        $parts = array_map('ucfirst', $parts);
        $camelCase = implode('', $parts);

        return "filter{$camelCase}";
    }

    protected function isDateField(string $field, $value): bool
    {
        $dateFields = $this->getDateFields();

        return in_array($field, $dateFields);
    }

    protected function applyDateFilter(Builder $query, string $field, string $value): void
    {
        $query->whereDate($field, $value);
    }

    protected function getSearchableFields(): array
    {
        return property_exists($this, 'searchableFields') ? $this->searchableFields : ['name'];
    }

    protected function getSortableFields(): array
    {
        return property_exists($this, 'sortableFields') ? $this->sortableFields : ['id', 'name', 'created_at'];
    }

    protected function getSortColumn(): string
    {
        return property_exists($this, 'sortColumn') ? $this->sortColumn : 'created_at';
    }

    protected function getDateFields(): array
    {
        return property_exists($this, 'dateFields') ? $this->dateFields : [];
    }
}
