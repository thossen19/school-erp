<?php

namespace App\Services\Student;

use App\Models\Student\StudentParent;
use App\Repositories\Student\StudentParentRepository;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class StudentParentService extends BaseService
{
    protected $repository;

    public function __construct(StudentParentRepository $repository)
    {
        $this->repository = $repository;
        parent::__construct($repository);
    }

    public function getParentsByStudent(int $studentId): Collection
    {
        return $this->repository->findByStudentId($studentId);
    }

    public function getStudentsByParent(int $parentId): Collection
    {
        return $this->repository->findByParentId($parentId);
    }

    public function linkParentToStudent(int $studentId, int $parentId, string $relationship = null): StudentParent
    {
        return $this->repository->create([
            'student_id' => $studentId,
            'parent_id' => $parentId,
            'relationship' => $relationship,
        ]);
    }

    public function getEmergencyContacts(int $studentId): Collection
    {
        return $this->repository->getEmergencyContacts($studentId);
    }
}
