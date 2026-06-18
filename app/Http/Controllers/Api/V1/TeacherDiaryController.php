<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Academic\TeacherDiary;
use App\Services\Academic\TeacherDiaryService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherDiaryController extends Controller
{
    use ApiResponseTrait;

    protected TeacherDiaryService $diaryService;

    public function __construct(TeacherDiaryService $diaryService)
    {
        $this->diaryService = $diaryService;
    }

    public function index(Request $request): JsonResponse
    {
        $entries = TeacherDiary::with('teacher', 'subject', 'class', 'section')->when($request->teacher_id, fn($q) => $q->where('teacher_id', $request->teacher_id))->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))->when($request->subject_id, fn($q) => $q->where('subject_id', $request->subject_id))->when($request->date, fn($q) => $q->whereDate('diary_date', $request->date))->when($request->date_from, fn($q) => $q->whereDate('diary_date', '>=', $request->date_from))->when($request->date_to, fn($q) => $q->whereDate('diary_date', '<=', $request->date_to))->orderBy('diary_date', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($entries, 'Teacher diary entries retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subject_id' => 'required|integer|exists:subjects,id',
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'diary_date' => 'required|date',
            'period' => 'nullable|string|max:50',
            'topic_covered' => 'required|string|max:2000',
            'teaching_method' => 'nullable|string|max:500',
            'student_participation' => 'nullable|string|max:1000',
            'homework_given' => 'nullable|string|max:1000',
            'remarks' => 'nullable|string|max:1000',
            'status' => 'nullable|string|in:draft,submitted,approved',
        ]);

        $entry = TeacherDiary::create(array_merge($validated, ['teacher_id' => $request->user()->id]));
        return $this->createdResponse($entry->load('teacher', 'subject', 'class'), 'Diary entry created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(TeacherDiary::with('teacher', 'subject', 'class', 'section')->findOrFail($id), 'Diary entry retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $entry = TeacherDiary::findOrFail($id);
        $validated = $request->validate([
            'topic_covered' => 'sometimes|string|max:2000',
            'teaching_method' => 'nullable|string|max:500',
            'student_participation' => 'nullable|string|max:1000',
            'homework_given' => 'nullable|string|max:1000',
            'remarks' => 'nullable|string|max:1000',
            'status' => 'nullable|string|in:draft,submitted,approved',
        ]);
        $entry->update($validated);
        return $this->updatedResponse($entry->fresh()->load('teacher', 'subject', 'class'), 'Diary entry updated');
    }

    public function destroy(int $id): JsonResponse
    {
        TeacherDiary::findOrFail($id)->delete();
        return $this->deletedResponse('Diary entry deleted');
    }
}
