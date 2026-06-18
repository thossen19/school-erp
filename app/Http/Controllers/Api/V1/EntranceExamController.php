<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Admission\EntranceExam;
use App\Models\Admission\EntranceExamResult;
use App\Services\Admission\EntranceExamService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EntranceExamController extends Controller
{
    use ApiResponseTrait;

    protected EntranceExamService $examService;

    public function __construct(EntranceExamService $examService)
    {
        $this->examService = $examService;
    }

    public function index(Request $request): JsonResponse
    {
        $exams = EntranceExam::with('class')->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))->when($request->status, fn($q) => $q->where('status', $request->status))->orderBy('exam_date', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($exams, 'Entrance exams retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|integer|exists:classes,id',
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_marks' => 'required|integer|min:0',
            'passing_marks' => 'required|integer|min:0|lte:max_marks',
            'description' => 'nullable|string|max:1000',
            'venue' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:scheduled,ongoing,completed,cancelled',
        ]);

        $exam = EntranceExam::create($validated);
        return $this->createdResponse($exam->load('class'), 'Entrance exam created');
    }

    public function show(int $id): JsonResponse
    {
        $exam = EntranceExam::with('class', 'results', 'results.student')->findOrFail($id);
        return $this->successResponse($exam, 'Entrance exam retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $exam = EntranceExam::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'exam_date' => 'sometimes|date',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i|after:start_time',
            'max_marks' => 'sometimes|integer|min:0',
            'passing_marks' => 'sometimes|integer|min:0|lte:max_marks',
            'description' => 'nullable|string|max:1000',
            'venue' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:scheduled,ongoing,completed,cancelled',
        ]);
        $exam->update($validated);
        return $this->updatedResponse($exam->fresh()->load('class'), 'Entrance exam updated');
    }

    public function destroy(int $id): JsonResponse
    {
        EntranceExam::findOrFail($id)->delete();
        return $this->deletedResponse('Entrance exam deleted');
    }

    public function publishResults(int $examId): JsonResponse
    {
        $exam = EntranceExam::findOrFail($examId);
        $exam->update(['status' => 'completed', 'results_published_at' => now()]);
        EntranceExamResult::where('entrance_exam_id', $examId)->update(['is_published' => true]);
        return $this->successResponse($exam->load('results'), 'Results published');
    }

    public function generateRankings(int $examId): JsonResponse
    {
        $rankings = $this->examService->generateRankings($examId);
        return $this->successResponse($rankings, 'Rankings generated');
    }
}
