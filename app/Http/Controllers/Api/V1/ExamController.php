<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Exam\StoreExamRequest;
use App\Models\Assessment\Exam;
use App\Services\Assessment\ExamService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    use ApiResponseTrait;

    protected ExamService $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
    }

    public function index(Request $request): JsonResponse
    {
        $exams = Exam::with('class', 'examType', 'academicYear')->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))->when($request->exam_type, fn($q) => $q->where('exam_type', $request->exam_type))->when($request->academic_year_id, fn($q) => $q->where('academic_year_id', $request->academic_year_id))->when($request->is_published, fn($q) => $q->where('is_published', $request->boolean('is_published')))->when($request->status, fn($q) => $q->where('status', $request->status))->orderBy('start_date', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($exams, 'Exams retrieved');
    }

    public function store(StoreExamRequest $request): JsonResponse
    {
        $exam = $this->examService->createExam($request->validated());
        return $this->createdResponse($exam->load('class', 'examType', 'subjects'), 'Exam created');
    }

    public function show(int $id): JsonResponse
    {
        $exam = Exam::with('class', 'examType', 'academicYear', 'subjects.subject', 'results', 'schedule')->findOrFail($id);
        return $this->successResponse($exam, 'Exam retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $exam = Exam::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'start_date' => 'sometimes|date|before:end_date',
            'end_date' => 'sometimes|date|after:start_date',
            'description' => 'nullable|string|max:1000',
            'max_marks' => 'sometimes|integer|min:0',
            'passing_marks' => 'sometimes|integer|min:0|lte:max_marks',
            'weightage' => 'nullable|numeric|min:0|max:100',
            'is_published' => 'boolean',
            'status' => 'nullable|string|in:scheduled,ongoing,completed,cancelled',
        ]);
        $exam->update($validated);

        if ($request->has('subjects')) {
            $this->examService->updateExamSubjects($exam->id, $request->subjects);
        }

        return $this->updatedResponse($exam->fresh()->load('class', 'examType', 'subjects.subject'), 'Exam updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Exam::findOrFail($id)->delete();
        return $this->deletedResponse('Exam deleted');
    }

    public function schedule(Request $request, int $examId): JsonResponse
    {
        $request->validate([
            'subjects' => 'required|array|min:1',
            'subjects.*.subject_id' => 'required|integer|exists:subjects,id',
            'subjects.*.date' => 'required|date',
            'subjects.*.start_time' => 'required|date_format:H:i',
            'subjects.*.end_time' => 'required|date_format:H:i|after:subjects.*.start_time',
            'subjects.*.room_id' => 'nullable|integer|exists:room_allocations,id',
            'subjects.*.max_marks' => 'required|integer|min:0',
            'subjects.*.passing_marks' => 'required|integer|min:0|lte:subjects.*.max_marks',
        ]);

        $schedule = $this->examService->createExamSchedule($examId, $request->subjects);
        return $this->createdResponse($schedule, 'Exam schedule created');
    }

    public function publishResults(int $examId): JsonResponse
    {
        $exam = Exam::findOrFail($examId);
        $exam->update(['is_published' => true]);
        $this->examService->publishResults($examId);
        return $this->successResponse($exam->load('results'), 'Results published');
    }

    public function generateRankings(int $examId): JsonResponse
    {
        $rankings = $this->examService->generateRankings($examId);
        return $this->successResponse($rankings, 'Rankings generated');
    }

    public function getGradeCard(int $examId, int $studentId): JsonResponse
    {
        $gradeCard = $this->examService->generateGradeCard($examId, $studentId);
        return $this->successResponse($gradeCard, 'Grade card generated');
    }
}
