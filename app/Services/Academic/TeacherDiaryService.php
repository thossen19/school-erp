<?php

namespace App\Services\Academic;

use App\Contracts\RepositoryInterface;
use App\Models\Academic\LessonPlan;
use App\Repositories\Academic\LessonPlanRepository;
use App\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TeacherDiaryService extends BaseService
{
    protected LessonPlanRepository $lessonPlanRepository;

    public function __construct(LessonPlanRepository $lessonPlanRepository)
    {
        parent::__construct();
        $this->lessonPlanRepository = $lessonPlanRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->lessonPlanRepository;
    }

    public function createEntry(array $data): LessonPlan
    {
        return DB::transaction(function () use ($data) {
            $data['status'] = $data['status'] ?? 'draft';
            $entry = $this->lessonPlanRepository->create($data);

            $this->logActivity('teacher_diary_entry_created', $entry);

            return $entry;
        });
    }

    public function getDiaryByDate(string $date): Collection
    {
        return $this->lessonPlanRepository->getByDateRange($date, $date);
    }

    public function getDiaryByTeacher(int $employeeId): Collection
    {
        return $this->lessonPlanRepository->findByTeacher($employeeId);
    }
}
