<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Exam\StoreExamResultRequest;
use App\Models\Assessment\ExamResult;
use App\Services\Assessment\ExamService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExamResultController extends Controller
{
    use ApiResponseTrait;

    protected ExamService $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
    }

    public function index(Request $request): JsonResponse
    {
        $results = ExamResult::with('exam', 'student:id,first_name,last_name,admission_no', 'subject')->when($request->exam_id, fn($q) => $q->where('exam_id', $request->exam_id))->when($request->student_id, fn($q) => $q->where('student_id', $request->student_id))->when($request->subject_id, fn($q) => $q->where('subject_id', $request->subject_id))->when($request->class_id, fn($q) => $q->whereHas('student', fn($q) => $q->where('class_id', $request->class_id)))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 50);

        return $this->paginatedResponse($results, 'Exam results retrieved');
    }

    public function store(StoreExamResultRequest $request): JsonResponse
    {
        $result = ExamResult::create($request->validated());
        $this->examService->calculateGrade($result);
        return $this->createdResponse($result->load('exam', 'student', 'subject'), 'Exam result recorded');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(ExamResult::with('exam', 'student', 'subject')->findOrFail($id), 'Exam result retrieved');
    }

    public function update(StoreExamResultRequest $request, int $id): JsonResponse
    {
        $result = ExamResult::findOrFail($id);
        $result->update($request->validated());
        $this->examService->calculateGrade($result);
        return $this->updatedResponse($result->fresh()->load('exam', 'student', 'subject'), 'Exam result updated');
    }

    public function destroy(int $id): JsonResponse
    {
        ExamResult::findOrFail($id)->delete();
        return $this->deletedResponse('Exam result deleted');
    }

    public function bulkEntry(Request $request): JsonResponse
    {
        $request->validate([
            'exam_id' => 'required|integer|exists:exams,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'results' => 'required|array|min:1',
            'results.*.student_id' => 'required|integer|exists:students,id',
            'results.*.marks_obtained' => 'required|numeric|min:0',
            'results.*.max_marks' => 'required|numeric|min:0',
            'results.*.is_absent' => 'boolean',
            'results.*.remarks' => 'nullable|string|max:500',
        ]);

        $results = $this->examService->bulkEntryResults($request->exam_id, $request->subject_id, $request->results);
        return $this->createdResponse($results, 'Bulk results entered');
    }

    public function getByExam(int $examId, Request $request): JsonResponse
    {
        $results = ExamResult::with('student:id,first_name,last_name,admission_no', 'subject')->where('exam_id', $examId)->when($request->subject_id, fn($q) => $q->where('subject_id', $request->subject_id))->get()->groupBy('student_id');

        return $this->successResponse($results, 'Results by exam');
    }

    public function getByStudent(int $studentId, Request $request): JsonResponse
    {
        $results = ExamResult::with('exam', 'subject')->where('student_id', $studentId)->when($request->exam_id, fn($q) => $q->where('exam_id', $request->exam_id))->when($request->academic_year_id, fn($q) => $q->whereHas('exam', fn($q) => $q->where('academic_year_id', $request->academic_year_id)))->orderBy('created_at', 'desc')->get();

        return $this->successResponse($results, 'Results by student');
    }
}
