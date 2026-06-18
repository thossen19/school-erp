<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    use ApiResponseTrait;

    public function create(): JsonResponse
    {
        $filename = 'backup-' . now()->format('Y-m-d-H-i-s') . '.sql';
        $path = storage_path("app/backups/{$filename}");

        if (!File::exists(storage_path('app/backups'))) {
            File::makeDirectory(storage_path('app/backups'), 0755, true);
        }

        $command = sprintf(
            'mysqldump -u%s %s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password') ? '-p' . config('database.connections.mysql.password') : '',
            config('database.connections.mysql.database'),
            $path
        );

        $output = null;
        $resultCode = null;
        exec($command, $output, $resultCode);

        if ($resultCode !== 0) {
            return $this->internalServerErrorResponse('Backup failed');
        }

        return $this->successResponse([
            'filename' => $filename,
            'path' => $path,
            'size' => File::size($path),
            'created_at' => now(),
        ], 'Backup created');
    }

    public function download(string $filename): JsonResponse
    {
        $path = storage_path("app/backups/{$filename}");

        if (!File::exists($path)) {
            return $this->notFoundResponse('Backup file not found');
        }

        return response()->download($path)->deleteFileAfterSend(false);
    }

    public function restore(Request $request): JsonResponse
    {
        $request->validate(['backup_file' => 'required|file|mimes:sql|max:102400']);

        $file = $request->file('backup_file');
        $content = $file->getContent();

        $command = sprintf(
            'mysql -u%s %s %s < %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password') ? '-p' . config('database.connections.mysql.password') : '',
            config('database.connections.mysql.database'),
            $file->getRealPath()
        );

        $output = null;
        $resultCode = null;
        exec($command, $output, $resultCode);

        if ($resultCode !== 0) {
            return $this->internalServerErrorResponse('Restore failed');
        }

        return $this->successResponse(null, 'Database restored from backup');
    }

    public function list(): JsonResponse
    {
        $path = storage_path('app/backups');

        if (!File::exists($path)) {
            return $this->successResponse([], 'No backups found');
        }

        $backups = collect(File::files($path))->filter(fn($file) => $file->getExtension() === 'sql')->map(fn($file) => [
                'filename' => $file->getFilename(),
                'size' => $file->getSize(),
                'size_formatted' => $this->formatBytes($file->getSize()),
                'created_at' => date('Y-m-d H:i:s', $file->getMTime()),
            ])->sortByDesc('created_at')->values();

        return $this->successResponse($backups, 'Backups list');
    }

    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
    }
}
