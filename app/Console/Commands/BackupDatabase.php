<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{
    protected $signature = 'backup:create {--compress : Compress the backup file}';
    protected $description = 'Create a database backup dump';

    public function handle(): int
    {
        $this->info('Starting database backup...');

        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');

        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $filename = "backup-{$database}-{$timestamp}.sql";
        $backupPath = storage_path("app/backups/{$filename}");

        if (!is_dir(dirname($backupPath))) {
            mkdir(dirname($backupPath), 0755, true);
        }

        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s --port=%s --single-transaction --routines --triggers --events %s > %s',
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($database),
            escapeshellarg($backupPath)
        );

        if ($this->option('compress')) {
            $command .= ' && gzip ' . escapeshellarg($backupPath);
            $filename .= '.gz';
            $backupPath .= '.gz';
        }

        $this->info("Running: mysqldump for {$database}...");
        $startTime = microtime(true);

        $output = null;
        $returnCode = 0;
        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            $this->error('Database backup failed with exit code ' . $returnCode);
            Log::error('Database backup failed', ['exit_code' => $returnCode, 'output' => implode("\n", $output)]);
            return self::FAILURE;
        }

        $elapsed = round(microtime(true) - $startTime, 2);
        $fileSize = file_exists($backupPath) ? round(filesize($backupPath) / 1024 / 1024, 2) : 0;

        $this->info("Backup completed in {$elapsed}s: {$filename} ({$fileSize} MB)");

        $this->cleanOldBackups();

        Log::info("Database backup created: {$filename} ({$fileSize} MB, {$elapsed}s)");

        return self::SUCCESS;
    }

    protected function cleanOldBackups(int $keepDays = 30): void
    {
        $backupDir = storage_path('app/backups');
        if (!is_dir($backupDir)) {
            return;
        }

        $cutoff = Carbon::now()->subDays($keepDays);
        $deleted = 0;

        foreach (glob($backupDir . '/backup-*.sql*') as $file) {
            $fileTime = Carbon::createFromTimestamp(filemtime($file));
            if ($fileTime->lt($cutoff)) {
                unlink($file);
                $deleted++;
            }
        }

        if ($deleted > 0) {
            $this->info("Cleaned up {$deleted} old backup files (older than {$keepDays} days).");
        }
    }
}
