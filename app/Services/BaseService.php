<?php

namespace App\Services;

use App\Contracts\BaseServiceInterface;
use App\Contracts\RepositoryInterface;
use App\Exceptions\ServiceException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

abstract class BaseService implements BaseServiceInterface
{
    protected RepositoryInterface $repository;

    protected string $cachePrefix = '';

    protected int $cacheTtl = 3600;

    protected bool $enableCache = false;

    protected ?string $activityEvent = null;

    abstract public function repository(): RepositoryInterface;

    public function __construct()
    {
        $this->repository = $this->repository();
        $this->cachePrefix = Str::snake(class_basename(static::class));
    }

    public function getAll(): Collection
    {
        $cacheKey = "{$this->cachePrefix}:all";

        if ($this->enableCache) {
            return Cache::remember($cacheKey, $this->cacheTtl, function () {
                return $this->repository->getAll();
            });
        }

        return $this->repository->getAll();
    }

    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        $cacheKey = "{$this->cachePrefix}:paginated:{$perPage}";

        if ($this->enableCache) {
            return Cache::remember($cacheKey, $this->cacheTtl, function () use ($perPage) {
                return $this->repository->getPaginated($perPage);
            });
        }

        return $this->repository->getPaginated($perPage);
    }

    public function find($id): ?Model
    {
        $cacheKey = "{$this->cachePrefix}:find:{$id}";

        if ($this->enableCache) {
            return Cache::remember($cacheKey, $this->cacheTtl, function () use ($id) {
                return $this->repository->getById($id);
            });
        }

        return $this->repository->getById($id);
    }

    public function create(array $data): Model
    {
        try {
            $result = $this->repository->create($data);

            $this->clearCache();
            $this->logActivity('created', $result);

            return $result;
        } catch (\Exception $e) {
            Log::error(class_basename(static::class) . '@create: ' . $e->getMessage(), [
                'data' => $data,
                'trace' => $e->getTraceAsString(),
            ]);
            throw new ServiceException("Failed to create record: " . $e->getMessage(), 500, $e);
        }
    }

    public function update($id, array $data): Model
    {
        try {
            $result = $this->repository->update($id, $data);

            $this->clearCache();
            $this->logActivity('updated', $result);

            return $result;
        } catch (\Exception $e) {
            Log::error(class_basename(static::class) . '@update: ' . $e->getMessage(), [
                'id' => $id,
                'data' => $data,
                'trace' => $e->getTraceAsString(),
            ]);
            throw new ServiceException("Failed to update record with ID {$id}: " . $e->getMessage(), 500, $e);
        }
    }

    public function delete($id): bool
    {
        try {
            $model = $this->repository->getById($id);
            $result = $this->repository->delete($id);

            $this->clearCache();
            $this->logActivity('deleted', $model);

            return $result;
        } catch (\Exception $e) {
            Log::error(class_basename(static::class) . '@delete: ' . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);
            throw new ServiceException("Failed to delete record with ID {$id}: " . $e->getMessage(), 500, $e);
        }
    }

    public function restore($id): Model
    {
        try {
            $model = $this->repository->getById($id);

            if (!method_exists($model, 'restore')) {
                throw new ServiceException("Model does not support soft deletes.");
            }

            $model->restore();

            $this->clearCache();
            $this->logActivity('restored', $model);

            return $model;
        } catch (\Exception $e) {
            Log::error(class_basename(static::class) . '@restore: ' . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);
            throw new ServiceException("Failed to restore record with ID {$id}: " . $e->getMessage(), 500, $e);
        }
    }

    public function bulkDelete(array $ids): bool
    {
        try {
            $result = $this->repository->bulkDelete($ids);

            $this->clearCache();
            $this->logActivity('bulk_deleted', ['ids' => $ids]);

            return $result;
        } catch (\Exception $e) {
            Log::error(class_basename(static::class) . '@bulkDelete: ' . $e->getMessage(), [
                'ids' => $ids,
                'trace' => $e->getTraceAsString(),
            ]);
            throw new ServiceException("Failed to bulk delete records: " . $e->getMessage(), 500, $e);
        }
    }

    public function export(string $format = 'csv')
    {
        $data = $this->repository->getAll();

        if ($format === 'csv') {
            return $this->exportToCsv($data);
        }

        if ($format === 'excel') {
            return $this->exportToExcel($data);
        }

        throw new ServiceException("Unsupported export format: {$format}");
    }

    public function import(array $data): bool
    {
        try {
            $result = $this->repository->bulkInsert($data);

            $this->clearCache();
            $this->logActivity('imported', ['count' => count($data)]);

            return $result;
        } catch (\Exception $e) {
            Log::error(class_basename(static::class) . '@import: ' . $e->getMessage(), [
                'data_count' => count($data),
                'trace' => $e->getTraceAsString(),
            ]);
            throw new ServiceException("Failed to import data: " . $e->getMessage(), 500, $e);
        }
    }

    protected function clearCache(): void
    {
        if ($this->enableCache) {
            Cache::tags($this->cachePrefix)->flush();
        }
    }

    protected function logActivity(string $action, $subject): void
    {
        if (!class_exists(\Spatie\Activitylog\Models\Activity::class)) {
            return;
        }

        try {
            $causer = auth()->user();
            $event = $this->activityEvent ?? $action;

            activity()->causedBy($causer)->performedOn($subject instanceof Model ? $subject : null)->withProperties([
                    'action' => $action,
                    'subject_type' => is_object($subject) ? get_class($subject) : gettype($subject),
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])->event($event)->log(class_basename(static::class) . ": {$action}");
        } catch (\Exception $e) {
            Log::warning('Failed to log activity: ' . $e->getMessage());
        }
    }

    protected function exportToCsv(Collection $data): string
    {
        $filename = storage_path("app/exports/{$this->cachePrefix}-" . now()->format('YmdHis') . '.csv');
        $directory = dirname($filename);

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $file = fopen($filename, 'w');

        if ($data->isNotEmpty()) {
            $first = $data->first();
            if ($first instanceof Model) {
                fputcsv($file, array_keys($first->toArray()));
            } elseif (is_array($first)) {
                fputcsv($file, array_keys($first));
            }

            foreach ($data as $row) {
                fputcsv($file, $row instanceof Model ? $row->toArray() : (array) $row);
            }
        }

        fclose($file);

        return $filename;
    }

    protected function exportToExcel(Collection $data): string
    {
        throw new ServiceException('Excel export requires PhpSpreadsheet. Use CSV format instead.');
    }

    public function withoutCache(): static
    {
        $this->enableCache = false;

        return $this;
    }

    public function withCache(int $ttl = 3600): static
    {
        $this->enableCache = true;
        $this->cacheTtl = $ttl;

        return $this;
    }
}
