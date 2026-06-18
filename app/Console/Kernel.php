<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('payroll:process')->monthly();
        $schedule->command('attendance:auto-mark')->daily();
        $schedule->command('fee:send-reminders')->daily();
        $schedule->command('backup:create')->daily();
        $schedule->command('cache:clean')->weekly();
        $schedule->command('activitylog:clean')->monthly();
        $schedule->command('leave:process-accruals')->monthly();
        $schedule->command('exam:send-notifications')->daily();
        $schedule->command('academic-year:sync')->daily();
        $schedule->command('attendance:generate-report')->dailyAt('23:00');
        $schedule->command('model:prune')->daily();
        $schedule->command('queue:prune-batches')->daily();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
