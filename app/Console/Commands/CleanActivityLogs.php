<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CleanActivityLogs extends Command
{
    protected $signature = 'activitylog:clean {--days=90 : Delete activity logs older than this many days}';
    protected $description = 'Clean old activity log entries from the database';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $cutoff = Carbon::now()->subDays($days);
        $cutoffStr = $cutoff->format('Y-m-d H:i:s');

        $this->info("Cleaning activity logs older than {$days} days (before {$cutoffStr})...");

        if (!$this->confirm("This will permanently delete activity log entries before {$cutoffStr}. Continue?", true)) {
            $this->info('Operation cancelled.');
            return self::SUCCESS;
        }

        try {
            $deleted = DB::table('activity_log')->where('created_at', '<', $cutoff)->delete();

            $this->info("Deleted {$deleted} old activity log entries.");
            Log::info("Activity log cleanup: deleted {$deleted} entries older than {$days} days.");
        } catch (\Exception $e) {
            $this->error("Failed to clean activity logs: " . $e->getMessage());
            Log::error("Activity log cleanup failed: " . $e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
