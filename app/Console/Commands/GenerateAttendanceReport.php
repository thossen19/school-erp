<?php

namespace App\Console\Commands;

use App\Models\Attendance\Attendance;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GenerateAttendanceReport extends Command
{
    protected $signature = 'attendance:generate-report {--date= : The date for the report (Y-m-d)}';
    protected $description = 'Generate daily attendance summary report for all schools';

    public function handle(): int
    {
        $date = $this->option('date') ? Carbon::parse($this->option('date')) : Carbon::yesterday();
        $dateStr = $date->format('Y-m-d');
        $this->info("Generating attendance report for {$dateStr}...");

        $schools = School::where('status', true)->get();

        if ($schools->isEmpty()) {
            $this->warn('No active schools found.');
            return self::SUCCESS;
        }

        $reports = [];
        $bar = $this->output->createProgressBar($schools->count());
        $bar->start();

        foreach ($schools as $school) {
            try {
                $attendances = Attendance::withoutSchoolScope()->where('school_id', $school->id)->whereDate('date', $date)->get();

                $total = $attendances->count();
                $present = $attendances->where('status', 'present')->count();
                $absent = $attendances->where('status', 'absent')->count();
                $late = $attendances->where('status', 'late')->count();
                $halfDay = $attendances->where('status', 'half_day')->count();

                $report = [
                    'school_id' => $school->id,
                    'school_name' => $school->name,
                    'date' => $dateStr,
                    'total_students' => $total,
                    'present' => $present,
                    'absent' => $absent,
                    'late' => $late,
                    'half_day' => $halfDay,
                    'attendance_percentage' => $total > 0 ? round(($present / $total) * 100, 2) : 0,
                    'generated_at' => now()->toDateTimeString(),
                ];

                $reports[] = $report;

                Log::channel('attendance')->info('Daily attendance report', $report);
                $this->line(" Report generated for {$school->name}");
            } catch (\Exception $e) {
                Log::error("Failed to generate attendance report for school {$school->id}: " . $e->getMessage());
                $this->error(" Failed for {$school->name}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $filename = "attendance-reports/daily-{$dateStr}.json";
        Storage::disk('local')->put($filename, json_encode($reports, JSON_PRETTY_PRINT));

        $this->info("Attendance report generated for {$dateStr}: {$filename}");
        $this->table(
            ['School', 'Total', 'Present', 'Absent', 'Late', 'Half Day', '%'],
            collect($reports)->map(fn($r) => [
                $r['school_name'],
                $r['total_students'],
                $r['present'],
                $r['absent'],
                $r['late'],
                $r['half_day'],
                $r['attendance_percentage'] . '%',
            ])
        );

        return self::SUCCESS;
    }
}
