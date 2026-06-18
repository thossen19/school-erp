<?php

namespace App\Services\Assessment;

use App\Contracts\RepositoryInterface;
use App\Models\Assessment\GradingSystem;
use App\Repositories\Assessment\GradingSystemRepository;
use App\Repositories\Assessment\ExamResultRepository;
use App\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GradingService extends BaseService
{
    protected GradingSystemRepository $gradingSystemRepository;
    protected ExamResultRepository $resultRepository;

    public function __construct(
        GradingSystemRepository $gradingSystemRepository,
        ExamResultRepository $resultRepository
    ) {
        parent::__construct();
        $this->gradingSystemRepository = $gradingSystemRepository;
        $this->resultRepository = $resultRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->gradingSystemRepository;
    }

    public function createGradingSystem(array $data): GradingSystem
    {
        return DB::transaction(function () use ($data) {
            if (!empty($data['is_default'])) {
                $this->gradingSystemRepository->setDefault(0);
            }

            $data['is_active'] = $data['is_active'] ?? true;
            $grade = $this->gradingSystemRepository->create($data);

            $this->logActivity('grading_system_created', $grade);

            return $grade;
        });
    }

    public function assignGrades(int $examId, int $gradingSystemId): Collection
    {
        return DB::transaction(function () use ($examId, $gradingSystemId) {
            $gradingSystem = $this->gradingSystemRepository->getById($gradingSystemId);
            $results = $this->resultRepository->getByExam($examId);

            $gradeRules = collect($gradingSystem->grades ?? []);

            foreach ($results as $result) {
                $grade = $gradeRules->first(fn($rule) =>
                    $result->percentage >= ($rule['min_percentage'] ?? 0) &&
                    $result->percentage <= ($rule['max_percentage'] ?? 100)
                );

                $gradeLetter = $grade['grade'] ?? 'F';
                $gradePoints = $grade['points'] ?? 0;

                $this->resultRepository->update($result->id, [
                    'grade' => $gradeLetter,
                    'grade_points' => $gradePoints,
                ]);
            }

            $updated = $this->resultRepository->getByExam($examId);

            $this->logActivity('grades_assigned', ['exam_id' => $examId, 'grading_system_id' => $gradingSystemId]);

            return $updated;
        });
    }

    public function calculatePoints(int $studentId, int $academicYearId): array
    {
        $summary = $this->resultRepository->getStudentPerformanceSummary($studentId, $academicYearId);

        return [
            'student_id' => $studentId,
            'academic_year_id' => $academicYearId,
            'total_points' => $summary['average_percentage'] ?? 0,
            'grade' => $this->getGradeFromPercentage($summary['average_percentage'] ?? 0),
        ];
    }

    public function getGradeReport(int $classId, int $examId): array
    {
        $rankings = $this->resultRepository->getClassRankings($examId, $classId);

        $report = [
            'exam_id' => $examId,
            'class_id' => $classId,
            'total_students' => $rankings->count(),
            'rankings' => $rankings->values()->map(fn($r, $i) => [
                'rank' => $i + 1,
                'student_id' => $r->student_id,
                'total_marks' => $r->total_marks,
            ]),
        ];

        $this->logActivity('grade_report_viewed', ['exam_id' => $examId, 'class_id' => $classId]);

        return $report;
    }

    private function getGradeFromPercentage(float $percentage): string
    {
        return match (true) {
            $percentage >= 90 => 'A+',
            $percentage >= 80 => 'A',
            $percentage >= 70 => 'B+',
            $percentage >= 60 => 'B',
            $percentage >= 50 => 'C+',
            $percentage >= 40 => 'C',
            $percentage >= 33 => 'D',
            default => 'F',
        };
    }
}
