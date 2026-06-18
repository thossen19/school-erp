<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Assessment\ContinuousAssessment;
use App\Services\Assessment\ContinuousAssessmentService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContinuousAssessmentController extends Controller
{
    use ApiResponseTrait;

    protected ContinuousAssessmentService $caService;

    public function __construct(ContinuousAssessmentService $caService)
    {
        $this->caService = $caService;
    }

    public function index(Request $request): JsonResponse
    {
        $assessments = ContinuousAssessment::with('student:id,first_name,last_name', 'subject', 'class')->when($request->student_id, fn($q) => $q->where('student_id', $request->student_id))->when($request->subject_id, fn($q) => $q->where('subject_id', $request->subject_id))->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))->when($request->type, fn($q) => $q->where('type', $request->type))->when($request->academic_year_id, fn($q) => $q->where('academic_year_id', $request->academic_year_id))->orderBy('assessment_date', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($assessments, 'Continuous assessments retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'academic_year_id' => 'required|integer|exists:academic_years,id',
            'type' => 'required|string|in:quiz,assignment,project,oral,practical,test,activity',
            'title' => 'required|string|max:255',
            'max_marks' => 'required|numeric|min:0',
            'marks_obtained' => 'required|numeric|min:0|lte:max_marks',
            'assessment_date' => 'required|date',
            'remarks' => 'nullable|string|max:500',
        ]);

        $assessment = ContinuousAssessment::create($validated);
        return $this->createdResponse($assessment->load('student', 'subject'), 'Assessment recorded');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            ContinuousAssessment::with('student', 'subject', 'class', 'section')->findOrFail($id),
            'Assessment retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $assessment = ContinuousAssessment::findOrFail($id);
        $validated = $request->validate([
            'type' => 'sometimes|string|in:quiz,assignment,project,oral,practical,test,activity',
            'title' => 'sometimes|string|max:255',
            'max_marks' => 'sometimes|numeric|min:0',
            'marks_obtained' => 'sometimes|numeric|min:0|lte:max_marks',
            'assessment_date' => 'sometimes|date',
            'remarks' => 'nullable|string|max:500',
        ]);
        $assessment->update($validated);
        return $this->updatedResponse($assessment->fresh()->load('student', 'subject'), 'Assessment updated');
    }

    public function destroy(int $id): JsonResponse
    {
        ContinuousAssessment::findOrFail($id)->delete();
        return $this->deletedResponse('Assessment deleted');
    }

    public function getProgressReport(int $studentId, Request $request): JsonResponse
    {
        $report = $this->caService->getProgressReport($studentId, $request->get('academic_year_id'));
        return $this->successResponse($report, 'Progress report generated');
    }
}
