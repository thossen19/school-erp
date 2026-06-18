<?php

namespace App\Services\Mis;

use App\Contracts\RepositoryInterface;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class MisService extends BaseService
{
    public function __construct()
    {
        $this->cachePrefix = \Illuminate\Support\Str::snake(class_basename(static::class));
    }

    public function repository(): RepositoryInterface
    {
        throw new \App\Exceptions\ServiceException("MIS service does not use a single repository.");
    }

    public function generateReport(string $type, array $parameters = []): array
    {
        return DB::transaction(function () use ($type, $parameters) {
            $report = match ($type) {
                'student_summary' => $this->generateStudentSummary($parameters),
                'fee_summary' => $this->generateFeeSummary($parameters),
                'attendance_summary' => $this->generateAttendanceSummary($parameters),
                'exam_summary' => $this->generateExamSummary($parameters),
                default => throw new \App\Exceptions\ServiceException("Unsupported report type: {$type}"),
            };

            $this->logActivity('report_generated', ['type' => $type, 'parameters' => $parameters]);

            return $report;
        });
    }

    public function scheduleReport(string $type, string $frequency, array $parameters = []): array
    {
        $schedule = [
            'type' => $type,
            'frequency' => $frequency,
            'parameters' => $parameters,
            'scheduled_at' => now(),
            'next_run' => $this->calculateNextRun($frequency),
            'created_by' => auth()->id(),
        ];

        $this->logActivity('report_scheduled', $schedule);

        return $schedule;
    }

    public function trackKpi(string $metric, array $data = []): array
    {
        $kpi = [
            'metric' => $metric,
            'value' => $data['value'] ?? 0,
            'target' => $data['target'] ?? 0,
            'period' => $data['period'] ?? now()->format('Y-m'),
            'achievement_percentage' => ($data['target'] ?? 0) > 0
                ? round((($data['value'] ?? 0) / ($data['target'] ?? 1)) * 100, 2)
                : 0,
        ];

        $this->logActivity('kpi_tracked', $kpi);

        return $kpi;
    }

    public function getDashboardData(): array
    {
        $data = [
            'total_students' => \App\Models\Student\Student::where('status', 'active')->count(),
            'total_employees' => \App\Models\Hr\Employee::where('status', 'active')->count(),
            'total_classes' => \App\Models\Academic\ClassModel::active()->count(),
            'today_present' => \App\Models\Attendance\Attendance::whereDate('date', now())->where('status', 'present')->count(),
            'today_absent' => \App\Models\Attendance\Attendance::whereDate('date', now())->where('status', 'absent')->count(),
            'pending_fees' => \App\Models\Fee\FeeCollection::where('balance_amount', '>', 0)->count(),
            'recent_admissions' => \App\Models\Admission\AdmissionForm::where('status', 'approved')->whereDate('approved_at', '>=', now()->subDays(30))->count(),
        ];

        $this->logActivity('dashboard_data_viewed', $data);

        return $data;
    }

    public function getAnalytics(string $type, string $period = 'monthly'): array
    {
        $analytics = match ($type) {
            'admission_trend' => $this->getAdmissionTrend($period),
            'fee_collection' => $this->getFeeCollectionAnalytics($period),
            'attendance_rate' => $this->getAttendanceRate($period),
            'exam_performance' => $this->getExamPerformance($period),
            default => [],
        };

        $this->logActivity('analytics_viewed', ['type' => $type, 'period' => $period]);

        return $analytics;
    }

    private function generateStudentSummary(array $parameters): array
    {
        $classId = $parameters['class_id'] ?? null;
        $query = \App\Models\Student\Student::where('status', 'active');

        if ($classId) {
            $query->where('class_id', $classId);
        }

        $students = $query->get();

        return [
            'total' => $students->count(),
            'by_gender' => $students->groupBy('gender')->map->count(),
            'by_class' => $students->groupBy('class_id')->map->count(),
        ];
    }

    private function generateFeeSummary(array $parameters): array
    {
        $startDate = $parameters['start_date'] ?? now()->startOfMonth();
        $endDate = $parameters['end_date'] ?? now()->endOfMonth();

        $collections = \App\Models\Fee\FeeCollection::whereBetween('payment_date', [$startDate, $endDate])->get();

        return [
            'total_collected' => $collections->sum('paid_amount'),
            'total_pending' => $collections->sum('balance_amount'),
            'total_transactions' => $collections->count(),
        ];
    }

    private function generateAttendanceSummary(array $parameters): array
    {
        $date = $parameters['date'] ?? now()->format('Y-m-d');
        $records = \App\Models\Attendance\Attendance::whereDate('date', $date)->get();

        return [
            'date' => $date,
            'present' => $records->where('status', 'present')->count(),
            'absent' => $records->where('status', 'absent')->count(),
            'late' => $records->where('status', 'late')->count(),
            'half_day' => $records->where('status', 'half_day')->count(),
        ];
    }

    private function generateExamSummary(array $parameters): array
    {
        $examId = $parameters['exam_id'] ?? null;
        if (!$examId) {
            return [];
        }

        $results = \App\Models\Assessment\ExamResult::where('exam_id', $examId)->get();

        return [
            'exam_id' => $examId,
            'total_students' => $results->count(),
            'passed' => $results->where('status', 'passed')->count(),
            'failed' => $results->where('status', 'failed')->count(),
            'average_percentage' => $results->avg('percentage'),
            'highest_percentage' => $results->max('percentage'),
        ];
    }

    private function getAdmissionTrend(string $period): array
    {
        $records = \App\Models\Admission\AdmissionForm::selectRaw(
            match ($period) {
                'daily' => "DATE(created_at) as label, COUNT(*) as count",
                'weekly' => "YEARWEEK(created_at) as label, COUNT(*) as count",
                'monthly' => "DATE_FORMAT(created_at, '%Y-%m') as label, COUNT(*) as count",
                'yearly' => "YEAR(created_at) as label, COUNT(*) as count",
                default => "DATE_FORMAT(created_at, '%Y-%m') as label, COUNT(*) as count",
            }
        )->groupBy('label')->orderBy('label')->get();

        return ['trend' => $records, 'period' => $period];
    }

    private function getFeeCollectionAnalytics(string $period): array
    {
        $records = \App\Models\Fee\FeeCollection::selectRaw(
            match ($period) {
                'daily' => "DATE(payment_date) as label, SUM(paid_amount) as total",
                'weekly' => "YEARWEEK(payment_date) as label, SUM(paid_amount) as total",
                'monthly' => "DATE_FORMAT(payment_date, '%Y-%m') as label, SUM(paid_amount) as total",
                'yearly' => "YEAR(payment_date) as label, SUM(paid_amount) as total",
                default => "DATE_FORMAT(payment_date, '%Y-%m') as label, SUM(paid_amount) as total",
            }
        )->where('status', 'paid')->groupBy('label')->orderBy('label')->get();

        return ['collection_trend' => $records, 'period' => $period];
    }

    private function getAttendanceRate(string $period): array
    {
        $records = \App\Models\Attendance\Attendance::selectRaw(
            match ($period) {
                'daily' => "DATE(date) as label, 
                    COUNT(*) as total, 
                    SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_count",
                'weekly' => "YEARWEEK(date) as label, 
                    COUNT(*) as total, 
                    SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_count",
                'monthly' => "DATE_FORMAT(date, '%Y-%m') as label, 
                    COUNT(*) as total, 
                    SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_count",
                default => "DATE_FORMAT(date, '%Y-%m') as label, 
                    COUNT(*) as total, 
                    SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_count",
            }
        )->groupBy('label')->orderBy('label')->get();

        return ['attendance_rate' => $records, 'period' => $period];
    }

    private function getExamPerformance(string $period): array
    {
        $records = \App\Models\Assessment\ExamResult::selectRaw(
            "exam_id, AVG(percentage) as avg_percentage, COUNT(*) as total_students,
             SUM(CASE WHEN status = 'passed' THEN 1 ELSE 0 END) as passed"
        )->groupBy('exam_id')->get();

        return ['exam_performance' => $records, 'period' => $period];
    }

    private function calculateNextRun(string $frequency): string
    {
        return match ($frequency) {
            'daily' => now()->addDay()->format('Y-m-d H:i:s'),
            'weekly' => now()->addWeek()->format('Y-m-d H:i:s'),
            'monthly' => now()->addMonth()->format('Y-m-d H:i:s'),
            'quarterly' => now()->addMonths(3)->format('Y-m-d H:i:s'),
            'yearly' => now()->addYear()->format('Y-m-d H:i:s'),
            default => now()->addDay()->format('Y-m-d H:i:s'),
        };
    }
}
