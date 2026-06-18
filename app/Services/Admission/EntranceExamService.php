<?php

namespace App\Services\Admission;

use App\Contracts\RepositoryInterface;
use App\Models\Admission\EntranceExam;
use App\Repositories\Admission\EntranceExamRepository;
use App\Repositories\Admission\EntranceExamResultRepository;
use App\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EntranceExamService extends BaseService
{
    protected EntranceExamRepository $examRepository;
    protected EntranceExamResultRepository $examResultRepository;

    public function __construct(
        EntranceExamRepository $examRepository,
        EntranceExamResultRepository $examResultRepository
    ) {
        parent::__construct();
        $this->examRepository = $examRepository;
        $this->examResultRepository = $examResultRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->examRepository;
    }

    public function createExam(array $data): EntranceExam
    {
        return DB::transaction(function () use ($data) {
            $data['status'] = $data['status'] ?? 'draft';

            $exam = $this->examRepository->create($data);

            $this->logActivity('entrance_exam_created', $exam);

            return $exam;
        });
    }

    public function scheduleExam(int $id, array $scheduleData): EntranceExam
    {
        return DB::transaction(function () use ($id, $scheduleData) {
            $exam = $this->examRepository->getById($id);

            $exam = $this->examRepository->update($id, array_merge($scheduleData, [
                'status' => 'scheduled',
                'scheduled_at' => now(),
            ]));

            $this->logActivity('entrance_exam_scheduled', $exam);

            return $exam;
        });
    }

    public function publishResults(int $examId, array $results): Collection
    {
        return DB::transaction(function () use ($examId, $results) {
            $exam = $this->examRepository->getById($examId);

            $created = collect();
            foreach ($results as $result) {
                $result['entrance_exam_id'] = $examId;
                $result['is_passed'] = $result['total_marks'] >= ($exam->passing_marks ?? 0);

                $created->push($this->examResultRepository->create($result));
            }

            $this->examRepository->update($examId, [
                'status' => 'results_published',
                'result_published_at' => now(),
            ]);

            $this->logActivity('entrance_exam_results_published', [
                'exam_id' => $examId,
                'results_count' => $created->count(),
            ]);

            return $created;
        });
    }

    public function generateRankings(int $examId, ?int $limit = null): Collection
    {
        $rankings = $this->examResultRepository->getRankings($examId);

        $ranked = $rankings->values()->map(function ($result, $index) {
            $result->rank = $index + 1;
            return $result;
        });

        if ($limit) {
            $ranked = $ranked->take($limit);
        }

        $this->logActivity('entrance_exam_rankings_generated', [
            'exam_id' => $examId,
            'count' => $ranked->count(),
        ]);

        return $ranked;
    }
}
