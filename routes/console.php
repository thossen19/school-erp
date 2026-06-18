<?php

use App\Models\Attendance\Attendance;
use App\Models\Fee\FeeCollection;
use App\Models\Fee\FeeDueTracking;
use App\Models\Payroll\Payroll;
use App\Models\Student\Student;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

// Process monthly payroll
Artisan::command('payroll:process {--month=} {--year=}', function () {
    $month = $this->option('month') ?: now()->subMonth()->month;
    $year = $this->option('year') ?: now()->subMonth()->year;

    $this->info("Processing payroll for {$month}/{$year}...");

    $employees = \App\Models\Hr\Employee::where('status', 'active')->get();
    $processed = 0;

    foreach ($employees as $employee) {
        $existing = Payroll::where('employee_id', $employee->id)
            ->where('month', $month)
            ->where('year', $year)
            ->first();

        if ($existing) {
            $this->warn("Payroll already exists for {$employee->full_name}");
            continue;
        }

        $structure = $employee->salaryStructure;
        if (!$structure) {
            $this->warn("No salary structure for {$employee->full_name}");
            continue;
        }

        $basicSalary = $structure->basic_salary;
        $allowances = $structure->components->where('type', 'earning')->sum('amount');
        $deductions = $structure->components->where('type', 'deduction')->sum('amount');

        Payroll::create([
            'employee_id' => $employee->id,
            'month' => $month,
            'year' => $year,
            'basic_salary' => $basicSalary,
            'allowances' => $structure->components->where('type', 'earning')->toArray(),
            'deductions' => $structure->components->where('type', 'deduction')->toArray(),
            'gross_salary' => $basicSalary + $allowances,
            'total_deductions' => $deductions,
            'net_salary' => ($basicSalary + $allowances) - $deductions,
            'status' => 'processed',
            'payment_date' => now(),
        ]);

        $processed++;
    }

    $this->info("Payroll processed for {$processed} employees.");
})->purpose('Process monthly payroll for all active employees');

// Send fee due reminders
Artisan::command('fees:send-reminders', function () {
    $this->info('Sending fee due reminders...');

    $duePayments = FeeDueTracking::with('student', 'feeStructure')
        ->where('due_date', '<=', now())
        ->where('status', 'pending')
        ->where('reminder_count', '<', 5)
        ->get();

    $sent = 0;
    foreach ($duePayments as $due) {
        try {
            $due->increment('reminder_count');
            $due->update(['last_reminder_sent_at' => now()]);

            if ($due->student && $due->student->email) {
                // Mail::to($due->student->email)->send(new FeeReminderMail($due));
            }

            $sent++;
        } catch (\Exception $e) {
            Log::error("Failed to send fee reminder for due #{$due->id}: {$e->getMessage()}");
        }
    }

    $this->info("Sent {$sent} fee reminders.");
})->purpose('Send fee due reminders to parents/students');

// Generate daily attendance report
Artisan::command('attendance:generate-report {--date=}', function () {
    $date = $this->option('date') ?: today()->toDateString();
    $this->info("Generating attendance report for {$date}...");

    $totalStudents = Student::where('status', 'active')->count();
    $present = Attendance::whereDate('date', $date)->where('status', 'present')->count();
    $absent = Attendance::whereDate('date', $date)->where('status', 'absent')->count();
    $late = Attendance::whereDate('date', $date)->where('status', 'late')->count();
    $halfDay = Attendance::whereDate('date', $date)->where('status', 'half_day')->count();
    $notMarked = $totalStudents - ($present + $absent + $late + $halfDay);

    $percentage = $totalStudents > 0 ? round(($present / $totalStudents) * 100, 2) : 0;

    $this->table(
        ['Metric', 'Value'],
        [
            ['Total Students', $totalStudents],
            ['Present', $present],
            ['Absent', $absent],
            ['Late', $late],
            ['Half Day', $halfDay],
            ['Not Marked', $notMarked],
            ['Attendance %', "{$percentage}%"],
        ]
    );

    Log::info("Attendance report for {$date}: {$percentage}% attendance");
})->purpose('Generate daily attendance summary report');

// Generate daily backup
Artisan::command('backup:database', function () {
    $this->info('Creating database backup...');

    $filename = 'backup-' . now()->format('Y-m-d-H-i-s') . '.sql';
    $path = storage_path("app/backups/{$filename}");

    if (!is_dir(storage_path('app/backups'))) {
        mkdir(storage_path('app/backups'), 0755, true);
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

    if ($resultCode === 0) {
        $this->info("Backup created: {$filename}");

        // Clean old backups (keep last 30)
        $backups = glob(storage_path('app/backups/*.sql'));
        if (count($backups) > 30) {
            $backupsToDelete = array_slice($backups, 0, count($backups) - 30);
            foreach ($backupsToDelete as $oldBackup) {
                unlink($oldBackup);
            }
            $this->info("Cleaned up " . count($backupsToDelete) . " old backups");
        }
    } else {
        $this->error('Backup failed!');
    }
})->purpose('Create database backup and clean old ones');

// Clean up old activity logs
Artisan::command('logs:clean {--days=90}', function () {
    $days = $this->option('days');
    $cutoff = now()->subDays($days);

    $deleted = \Spatie\Activitylog\Models\Activity::where('created_at', '<', $cutoff)->delete();

    $this->info("Deleted {$deleted} activity log entries older than {$days} days.");
})->purpose('Delete activity logs older than specified days');

// Schedule definitions
Schedule::command('payroll:process')->monthlyOn(1, '02:00')->withoutOverlapping();
Schedule::command('fees:send-reminders')->dailyAt('09:00');
Schedule::command('attendance:generate-report')->dailyAt('23:00');
Schedule::command('backup:database')->dailyAt('01:00');
Schedule::command('logs:clean')->weekly();
