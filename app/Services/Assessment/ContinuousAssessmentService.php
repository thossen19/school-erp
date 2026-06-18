<?php

namespace App\Services\Assessment;

use App\Contracts\RepositoryInterface;
use App\Models\Assessment\ContinuousAssessment;
use App\Repositories\Assessment\ContinuousAssessmentRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class ContinuousAssessmentService extends BaseService
{
    protected ContinuousAssessmentRepository $caRepository;

    public function __construct(ContinuousAssessmentRepository $caRepository)
    {
        parent::__construct();
        $this->caRepository = $caRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->caRepository;
    }

    public function recordAssessment(array $data): ContinuousAssessment
    {
        return DB::transaction(function () use ($data) {
            $assessment = $this->caRepository->create($data);

            $this->logActivity('continuous_assessment_recorded', $assessment);

            return $assessment;
        });
    }

    public function calculateAverage(int $studentId, int $subjectId, ?int $termId = null): array
    {
        $average = $this->caRepository->getStudentAverage($studentId, $subjectId);
        $classAverage = $this->caRepository->getClassAverage(
            $this->getStudentClassId($studentId),
            $subjectId
        );

        $result = [
            'student_id' => $studentId,
            'subject_id' => $subjectId,
            'average' => round($average, 2),
            'class_average' => round($classAverage, 2),
            'difference' => round($average - $classAverage, 2),
        ];

        $this->logActivity('continuous_assessment_average_calculated', $result);

        return $result;
    }

    public function getProgressReport(int $studentId, int $subjectId): array
    {
        $assessments = $this->caRepository->getByStudentAndSubject($studentId, $subjectId);

        $totalMarks = $assessments->sum('marks_obtained');
        $totalPossible = $assessments->sum('max_marks');
        $average = $assessments->avg('marks_obtained');

        $report = [
            'student_id' => $studentId,
            'subject_id' => $subjectId,
            'total_assessments' => $assessments->count(),
            'total_marks_obtained' => $totalMarks,
            'total_max_marks' => $totalPossible,
            'overall_percentage' => $totalPossible > 0 ? round(($totalMarks / $totalPossible) * 100, 2) : 0,
            'average_score' => round($average, 2),
            'trend' => $this->calculateTrend($assessments),
            'assessments' => $assessments,
        ];

        $this->logActivity('progress_report_viewed', ['student_id' => $studentId, 'subject_id' => $subjectId]);

        return $report;
    }

    private function calculateTrend($assessments): string
    {
        if ($assessments->count() < 3) {
            return 'insufficient_data';
        }

        $scores = $assessments->pluck('marks_obtained');
        $firstHalf = $scores->take(floor($scores->count() / 2))->avg();
        $secondHalf = $scores->skip(floor($scores->count() / 2))->avg();

        if ($secondHalf > $firstHalf) return 'improving';
        if ($secondHalf < $firstHalf) return 'declining';
        return 'stable';
    }

    private function getStudentClassId(int $studentId): int
    {
        $student = \App\Models\Student\Student::find($studentId);
        return $student ? $student->class_id : 0;
    }
}
