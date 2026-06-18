<?php

namespace App\Console\Commands;

use App\Models\AcademicYear;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncAcademicYear extends Command
{
    protected $signature = 'academic-year:sync {--school-id= : Sync only for a specific school}';
    protected $description = 'Sync academic year settings and auto-advance to next year if needed';

    public function handle(): int
    {
        $this->info('Syncing academic year settings...');

        $schools = $this->option('school-id')
            ? School::where('id', $this->option('school-id'))->get()
            : School::where('status', true)->get();

        if ($schools->isEmpty()) {
            $this->warn('No active schools found.');
            return self::SUCCESS;
        }

        $today = Carbon::today();
        $updated = 0;
        $created = 0;

        $bar = $this->output->createProgressBar($schools->count());
        $bar->start();

        foreach ($schools as $school) {
            try {
                $currentYear = AcademicYear::withoutSchoolScope()->where('school_id', $school->id)->where('is_current', true)->first();

                if ($currentYear && $today->greaterThan($currentYear->end_date)) {
                    $currentYear->update(['is_current' => false]);
                    $updated++;

                    $nextYear = AcademicYear::withoutSchoolScope()->where('school_id', $school->id)->where('start_date', $currentYear->end_date->addDay()->format('Y-m-d'))->first();

                    if (!$nextYear) {
                        $startYear = $currentYear->end_date->format('Y');
                        $endYear = $currentYear->end_date->addYear()->format('Y');

                        $nextYear = AcademicYear::withoutSchoolScope()->create([
                            'school_id' => $school->id,
                            'name' => "{$startYear}-{$endYear}",
                            'start_date' => $currentYear->end_date->addDay(),
                            'end_date' => $currentYear->end_date->copy()->addYear(),
                            'is_current' => true,
                            'status' => true,
                        ]);
                        $created++;
                    } else {
                        $nextYear->update(['is_current' => true]);
                        $updated++;
                    }

                    $this->line(" Academic year advanced for {$school->name}");
                } elseif (!$currentYear) {
                    $year = $today->format('Y');
                    $nextYear = $today->copy()->addYear()->format('Y');

                    AcademicYear::withoutSchoolScope()->create([
                        'school_id' => $school->id,
                        'name' => "{$year}-{$nextYear}",
                        'start_date' => $today->copy()->startOfYear(),
                        'end_date' => $today->copy()->endOfYear(),
                        'is_current' => true,
                        'status' => true,
                    ]);
                    $created++;
                    $this->line(" Created default academic year for {$school->name}");
                }
            } catch (\Exception $e) {
                Log::error("Academic year sync failed for school {$school->id}: " . $e->getMessage());
                $this->error(" Failed for {$school->name}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Academic year sync completed: {$updated} updated, {$created} created.");

        return self::SUCCESS;
    }
}
