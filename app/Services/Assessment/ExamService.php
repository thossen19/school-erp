<?php

namespace App\Services\Assessment;

use App\Contracts\RepositoryInterface;
use App\Models\Assessment\Exam;
use App\Repositories\Assessment\ExamRepository;
use App\Repositories\Assessment\ExamScheduleRepository;
use App\Repositories\Assessment\ExamResultRepository;
use App\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ExamService extends BaseService
{
    protected ExamRepository $examRepository;
    protected ExamScheduleRepository $scheduleRepository;
    protected ExamResultRepository $resultRepository;

    public function __construct(
        ExamRepository $examRepository,
        ExamScheduleRepository $scheduleRepository,
        ExamResultRepository $resultRepository
    ) {
        $this->examRepository = $examRepository;
        $this->scheduleRepository = $scheduleRepository;
        $this->resultRepository = $resultRepository;
        parent::__construct();
    }

    public function repository(): RepositoryInterface
    {
        return $this->examRepository;
    }

    public function createExam(array $data): Exam
    {
        return DB::transaction(function () use ($data) {
            $exam = $this->examRepository->create($data);

            if (isset($data['schedule']) && is_array($data['schedule'])) {
                foreach ($data['schedule'] as $schedule) {
                    $schedule['exam_id'] = $exam->id;
                    $this->scheduleRepository->create($schedule);
                }
            }

            $this->logActivity('exam_created', $exam);

            return $exam;
        });
    }

    public function scheduleExam(int $examId, array $schedules): Collection
    {
        return DB::transaction(function () use ($examId, $schedules) {
            $exam = $this->examRepository->getById($examId);

            $created = collect();
            foreach ($schedules as $schedule) {
                $hasConflict = $this->scheduleRepository->checkConflict(
                    $schedule['class_id'],
                    $schedule['exam_date'],
                    $schedule['start_time'],
                    $schedule['end_time']
                );

                if ($hasConflict) {
                    throw new \App\Exceptions\ServiceException(
                        "Schedule conflict for class {$schedule['class_id']} on {$schedule['exam_date']}"
                    );
                }

                $schedule['exam_id'] = $examId;
                $created->push($this->scheduleRepository->create($schedule));
            }

            $this->examRepository->update($examId, ['is_scheduled' => true]);

            $this->logActivity('exam_scheduled', ['exam_id' => $examId, 'count' => $created->count()]);

            return $created;
        });
    }

    public function publishResults(int $examId, array $results): Collection
    {
        return DB::transaction(function () use ($examId, $results) {
            $exam = $this->examRepository->getById($examId);

            $created = collect();
            foreach ($results as $result) {
                $result['exam_id'] = $examId;
                $result['percentage'] = $result['total_marks'] > 0
                    ? round(($result['marks_obtained'] / $result['total_marks']) * 100, 2)
                    : 0;
                $result['status'] = $result['percentage'] >= ($exam->passing_percentage ?? 40) ? 'passed' : 'failed';
                $created->push($this->resultRepository->create($result));
            }

            $this->examRepository->publishResult($examId);

            $this->logActivity('exam_results_published', ['exam_id' => $examId, 'count' => $created->count()]);

            return $created;
        });
    }

    public function generateRankings(int $examId): Collection
    {
        $rankings = $this->resultRepository->getRankings($examId);

        $ranked = $rankings->values()->map(function ($result, $index) {
            $result->rank = $index + 1;
            return $result;
        });

        $this->logActivity('exam_rankings_generated', ['exam_id' => $examId]);

        return $ranked;
    }

    public function generateGradeCard(int $studentId, int $examId): array
    {
        $exam = $this->examRepository->getById($examId);
        $results = $this->resultRepository->getByExamAndStudent($examId, $studentId);

        $totalMarks = $results->sum('marks_obtained');
        $grandTotal = $results->sum('total_marks');
        $percentage = $grandTotal > 0 ? round(($totalMarks / $grandTotal) * 100, 2) : 0;

        $gradeCard = [
            'exam' => $exam->toArray(),
            'student_id' => $studentId,
            'subjects' => $results,
            'total_marks' => $totalMarks,
            'grand_total' => $grandTotal,
            'percentage' => $percentage,
            'status' => $percentage >= ($exam->passing_percentage ?? 40) ? 'passed' : 'failed',
        ];

        $this->logActivity('grade_card_generated', ['exam_id' => $examId, 'student_id' => $studentId]);

        return $gradeCard;
    }
}
