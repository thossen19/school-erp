<?php

namespace App\Services\Fee;

use App\Contracts\RepositoryInterface;
use App\Models\Fee\Scholarship;
use App\Repositories\Fee\ScholarshipRepository;
use App\Repositories\Student\StudentRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class ScholarshipService extends BaseService
{
    protected ScholarshipRepository $scholarshipRepository;
    protected StudentRepository $studentRepository;

    public function __construct(
        ScholarshipRepository $scholarshipRepository,
        StudentRepository $studentRepository
    ) {
        parent::__construct();
        $this->scholarshipRepository = $scholarshipRepository;
        $this->studentRepository = $studentRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->scholarshipRepository;
    }

    public function createScholarship(array $data): Scholarship
    {
        return DB::transaction(function () use ($data) {
            $scholarship = $this->scholarshipRepository->create($data);

            $this->logActivity('scholarship_created', $scholarship);

            return $scholarship;
        });
    }

    public function awardScholarship(int $scholarshipId, int $studentId): array
    {
        return DB::transaction(function () use ($scholarshipId, $studentId) {
            $scholarship = $this->scholarshipRepository->getById($scholarshipId);
            $student = $this->studentRepository->getById($studentId);

            if ($student->scholarships()->where('scholarship_id', $scholarshipId)->exists()) {
                throw new \App\Exceptions\ServiceException("Student already has this scholarship awarded.");
            }

            $student->scholarships()->attach($scholarshipId, [
                'awarded_at' => now(),
                'status' => 'active',
            ]);

            $this->logActivity('scholarship_awarded', [
                'scholarship_id' => $scholarshipId,
                'student_id' => $studentId,
            ]);

            return ['scholarship' => $scholarship, 'student' => $student];
        });
    }

    public function revokeScholarship(int $scholarshipId, int $studentId, string $reason): array
    {
        return DB::transaction(function () use ($scholarshipId, $studentId, $reason) {
            $scholarship = $this->scholarshipRepository->getById($scholarshipId);
            $student = $this->studentRepository->getById($studentId);

            $student->scholarships()->updateExistingPivot($scholarshipId, [
                'status' => 'revoked',
                'revoked_at' => now(),
                'revocation_reason' => $reason,
            ]);

            $this->logActivity('scholarship_revoked', [
                'scholarship_id' => $scholarshipId,
                'student_id' => $studentId,
                'reason' => $reason,
            ]);

            return ['scholarship' => $scholarship, 'student' => $student];
        });
    }

    public function getScholarshipReport(int $scholarshipId): array
    {
        $scholarship = $this->scholarshipRepository->getStudentsByScholarship($scholarshipId);

        $students = $scholarship->students ?? collect();
        $activeAwards = $students->filter(fn($s) => $s->pivot->status === 'active');

        $report = [
            'scholarship' => $scholarship->toArray(),
            'total_awarded' => $students->count(),
            'active_awards' => $activeAwards->count(),
            'revoked_awards' => $students->count() - $activeAwards->count(),
        ];

        $this->logActivity('scholarship_report_viewed', $report);

        return $report;
    }
}
