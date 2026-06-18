<?php

namespace App\Services\Student;

use App\Contracts\RepositoryInterface;
use App\Models\Student\StudentHouse;
use App\Repositories\Student\StudentHouseRepository;
use App\Repositories\Student\StudentRepository;
use App\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StudentHouseService extends BaseService
{
    protected StudentHouseRepository $houseRepository;
    protected StudentRepository $studentRepository;

    public function __construct(
        StudentHouseRepository $houseRepository,
        StudentRepository $studentRepository
    ) {
        parent::__construct();
        $this->houseRepository = $houseRepository;
        $this->studentRepository = $studentRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->houseRepository;
    }

    public function assignHouse(int $studentId, int $houseId): StudentHouse
    {
        return DB::transaction(function () use ($studentId, $houseId) {
            $house = $this->houseRepository->getById($houseId);
            $student = $this->studentRepository->getById($studentId);

            $this->studentRepository->update($studentId, ['house_id' => $houseId]);

            $this->logActivity('house_assigned_to_student', [
                'student_id' => $studentId,
                'house_id' => $houseId,
                'house_name' => $house->name,
            ]);

            return $house;
        });
    }

    public function getHouseStudents(int $houseId): Collection
    {
        $house = $this->houseRepository->getHouseWithMembers($houseId);
        return $house ? $house->students : collect();
    }

    public function getHouseReport(int $houseId): array
    {
        $house = $this->houseRepository->getHouseWithMembers($houseId);

        if (!$house) {
            return [];
        }

        $report = [
            'house' => $house->toArray(),
            'total_students' => $house->students->count(),
            'students' => $house->students,
        ];

        $this->logActivity('house_report_viewed', $house);

        return $report;
    }
}
