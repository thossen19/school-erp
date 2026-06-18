<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HasMediaTrait
{
    protected string $mediaDisk = 'public';

    protected array $mediaFiles = [];

    public static function bootHasMediaTrait(): void
    {
        static::saved(function (Model $model) {
            $model->processMediaUploads();
        });

        static::deleted(function (Model $model) {
            $model->deleteAllMedia();
        });
    }

    public function setMediaDisk(string $disk): static
    {
        $this->mediaDisk = $disk;

        return $this;
    }

    public function getMediaDisk(): string
    {
        return property_exists($this, 'mediaDisk') ? $this->mediaDisk : 'public';
    }

    public function uploadMedia(UploadedFile $file, string $path = '', string $name = null): string
    {
        $filename = $name ?? $this->generateMediaFilename($file);
        $path = trim($path, '/');
        $destination = $path ? "{$path}/{$filename}" : $filename;

        Storage::disk($this->getMediaDisk())->put($destination, file_get_contents($file->getRealPath()));

        return $destination;
    }

    public function uploadMultipleMedia(array $files, string $path = ''): array
    {
        $paths = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $paths[] = $this->uploadMedia($file, $path);
            }
        }

        return $paths;
    }

    public function deleteMedia(string $path): bool
    {
        if (Storage::disk($this->getMediaDisk())->exists($path)) {
            return Storage::disk($this->getMediaDisk())->delete($path);
        }

        return false;
    }

    public function deleteMultipleMedia(array $paths): bool
    {
        foreach ($paths as $path) {
            $this->deleteMedia($path);
        }

        return true;
    }

    public function deleteAllMedia(): bool
    {
        $mediaColumns = $this->getMediaColumns();

        foreach ($mediaColumns as $column) {
            if (isset($this->{$column}) && $this->{$column}) {
                $this->deleteMedia($this->{$column});
            }
        }

        return true;
    }

    public function getMediaUrl(string $path): string
    {
        return Storage::disk($this->getMediaDisk())->url($path);
    }

    public function mediaExists(string $path): bool
    {
        return Storage::disk($this->getMediaDisk())->exists($path);
    }

    public function setMediaFile(string $column, UploadedFile $file, string $path = ''): static
    {
        $this->mediaFiles[] = [
            'column' => $column,
            'file' => $file,
            'path' => $path,
        ];

        return $this;
    }

    protected function processMediaUploads(): void
    {
        foreach ($this->mediaFiles as $media) {
            if (isset($this->{$media['column']}) && $this->{$media['column']}) {
                $this->deleteMedia($this->{$media['column']});
            }

            $uploaded = $this->uploadMedia($media['file'], $media['path']);
            $this->forceFill([$media['column'] => $uploaded])->saveQuietly();
        }

        $this->mediaFiles = [];
    }

    protected function getMediaColumns(): array
    {
        return property_exists($this, 'mediaColumns') ? $this->mediaColumns : [];
    }

    protected function generateMediaFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $random = Str::random(40);

        return "{$random}.{$extension}";
    }
}
