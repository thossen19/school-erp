<?php

namespace App\Services\Academic;

use App\Contracts\RepositoryInterface;
use App\Models\Academic\Assignment;
use App\Repositories\Academic\AssignmentRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class AssignmentService extends BaseService
{
    protected AssignmentRepository $assignmentRepository;

    public function __construct(AssignmentRepository $assignmentRepository)
    {
        parent::__construct();
        $this->assignmentRepository = $assignmentRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->assignmentRepository;
    }

    public function createAssignment(array $data): Assignment
    {
        return DB::transaction(function () use ($data) {
            $data['status'] = $data['status'] ?? 'active';
            $assignment = $this->assignmentRepository->create($data);
            $this->logActivity('assignment_created', $assignment);
            return $assignment;
        });
    }

    public function submitAssignment(int $assignmentId, int $studentId, array $submissionData): \App\Models\Academic\AssignmentSubmission
    {
        return DB::transaction(function () use ($assignmentId, $studentId, $submissionData) {
            $assignment = $this->assignmentRepository->getById($assignmentId);

            $submission = \App\Models\Academic\AssignmentSubmission::create([
                'assignment_id' => $assignmentId,
                'student_id' => $studentId,
                'submission_date' => now(),
                'content' => $submissionData['content'] ?? null,
                'file_path' => $submissionData['file_path'] ?? null,
                'status' => 'submitted',
            ]);

            $this->logActivity('assignment_submitted', $submission);

            return $submission;
        });
    }

    public function gradeSubmission(int $submissionId, array $gradeData): \App\Models\Academic\AssignmentSubmission
    {
        return DB::transaction(function () use ($submissionId, $gradeData) {
            $submission = \App\Models\Academic\AssignmentSubmission::findOrFail($submissionId);

            $submission->update([
                'marks_obtained' => $gradeData['marks_obtained'],
                'total_marks' => $gradeData['total_marks'] ?? $submission->total_marks,
                'feedback' => $gradeData['feedback'] ?? null,
                'graded_by' => auth()->id(),
                'graded_at' => now(),
                'status' => 'graded',
            ]);

            $this->logActivity('assignment_submission_graded', $submission);

            return $submission;
        });
    }

    public function getAssignmentReport(int $classId, int $subjectId): array
    {
        $assignments = $this->assignmentRepository->getByClassAndSubject($classId, $subjectId);

        $report = [
            'class_id' => $classId,
            'subject_id' => $subjectId,
            'total_assignments' => $assignments->count(),
            'active_assignments' => $assignments->where('status', 'active')->count(),
            'closed_assignments' => $assignments->where('status', 'closed')->count(),
            'overdue' => $assignments->where('due_date', '<', now())->where('status', 'active')->count(),
            'assignments' => $assignments,
        ];

        $this->logActivity('assignment_report_viewed', $report);

        return $report;
    }
}
