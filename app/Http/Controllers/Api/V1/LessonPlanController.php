<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Academic\LessonPlan;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonPlanController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $plans = LessonPlan::with('subject', 'class', 'teacher')->when($request->subject_id, fn($q) => $q->where('subject_id', $request->subject_id))->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))->when($request->teacher_id, fn($q) => $q->where('created_by', $request->teacher_id))->when($request->status, fn($q) => $q->where('status', $request->status))->orderBy('lesson_date', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($plans, 'Lesson plans retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subject_id' => 'required|integer|exists:subjects,id',
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'title' => 'required|string|max:255',
            'topic' => 'required|string|max:500',
            'objectives' => 'nullable|string|max:2000',
            'materials' => 'nullable|string|max:2000',
            'activities' => 'nullable|string|max:5000',
            'assessment_method' => 'nullable|string|max:1000',
            'homework' => 'nullable|string|max:1000',
            'lesson_date' => 'required|date',
            'duration_minutes' => 'nullable|integer|min:5|max:480',
            'status' => 'nullable|string|in:draft,published,completed',
            'notes' => 'nullable|string|max:2000',
        ]);

        $plan = LessonPlan::create(array_merge($validated, ['created_by' => $request->user()->id]));
        return $this->createdResponse($plan->load('subject', 'class', 'teacher'), 'Lesson plan created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(LessonPlan::with('subject', 'class', 'section', 'teacher')->findOrFail($id), 'Lesson plan retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $plan = LessonPlan::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'topic' => 'sometimes|string|max:500',
            'objectives' => 'nullable|string|max:2000',
            'materials' => 'nullable|string|max:2000',
            'activities' => 'nullable|string|max:5000',
            'assessment_method' => 'nullable|string|max:1000',
            'homework' => 'nullable|string|max:1000',
            'lesson_date' => 'sometimes|date',
            'duration_minutes' => 'nullable|integer|min:5|max:480',
            'status' => 'nullable|string|in:draft,published,completed',
            'notes' => 'nullable|string|max:2000',
        ]);
        $plan->update($validated);
        return $this->updatedResponse($plan->fresh()->load('subject', 'class', 'teacher'), 'Lesson plan updated');
    }

    public function destroy(int $id): JsonResponse
    {
        LessonPlan::findOrFail($id)->delete();
        return $this->deletedResponse('Lesson plan deleted');
    }
}
