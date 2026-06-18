<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Academic\Assignment;
use App\Models\Academic\AssignmentSubmission;
use App\Services\Academic\AssignmentService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    use ApiResponseTrait;

    protected AssignmentService $assignmentService;

    public function __construct(AssignmentService $assignmentService)
    {
        $this->assignmentService = $assignmentService;
    }

    public function index(Request $request): JsonResponse
    {
        $assignments = Assignment::with('subject', 'class', 'teacher')->when($request->subject_id, fn($q) => $q->where('subject_id', $request->subject_id))->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))->when($request->teacher_id, fn($q) => $q->where('created_by', $request->teacher_id))->when($request->status, fn($q) => $q->where('status', $request->status))->orderBy('due_date', 'asc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($assignments, 'Assignments retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'subject_id' => 'required|integer|exists:subjects,id',
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'due_date' => 'required|date|after:today',
            'max_marks' => 'required|integer|min:0',
            'passing_marks' => 'nullable|integer|min:0|lte:max_marks',
            'type' => 'nullable|string|in:homework,classwork,project,research,other',
            'status' => 'nullable|string|in:draft,published,closed',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip|max:20480',
        ]);

        $assignment = Assignment::create(array_merge($validated, ['created_by' => $request->user()->id]));
        return $this->createdResponse($assignment->load('subject', 'class', 'teacher'), 'Assignment created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            Assignment::with('subject', 'class', 'section', 'teacher', 'submissions.student')->findOrFail($id),
            'Assignment retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $assignment = Assignment::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:5000',
            'due_date' => 'sometimes|date|after:today',
            'max_marks' => 'sometimes|integer|min:0',
            'passing_marks' => 'nullable|integer|min:0|lte:max_marks',
            'type' => 'nullable|string|in:homework,classwork,project,research,other',
            'status' => 'nullable|string|in:draft,published,closed',
        ]);
        $assignment->update($validated);
        return $this->updatedResponse($assignment->fresh()->load('subject', 'class'), 'Assignment updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Assignment::findOrFail($id)->delete();
        return $this->deletedResponse('Assignment deleted');
    }

    public function submit(Request $request, int $assignmentId): JsonResponse
    {
        $request->validate([
            'content' => 'nullable|string|max:5000',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png,zip|max:20480',
        ]);

        $submission = AssignmentSubmission::create([
            'assignment_id' => $assignmentId,
            'student_id' => $request->user()->id,
            'content' => $request->content,
            'submitted_at' => now(),
            'status' => 'submitted',
        ]);

        return $this->createdResponse($submission, 'Assignment submitted');
    }

    public function grade(Request $request, int $assignmentId, int $submissionId): JsonResponse
    {
        $request->validate([
            'marks_obtained' => 'required|numeric|min:0',
            'feedback' => 'nullable|string|max:2000',
            'remarks' => 'nullable|string|max:500',
        ]);

        $submission = AssignmentSubmission::where('assignment_id', $assignmentId)->findOrFail($submissionId);
        $submission->update(array_merge($request->validated(), ['graded_by' => $request->user()->id, 'graded_at' => now(), 'status' => 'graded']));

        return $this->successResponse($submission, 'Assignment graded');
    }

    public function getSubmissions(int $assignmentId, Request $request): JsonResponse
    {
        $submissions = AssignmentSubmission::with('student:id,first_name,last_name,admission_no')->where('assignment_id', $assignmentId)->when($request->status, fn($q) => $q->where('status', $request->status))->orderBy('submitted_at', 'desc')->get();

        return $this->successResponse($submissions, 'Submissions retrieved');
    }
}
