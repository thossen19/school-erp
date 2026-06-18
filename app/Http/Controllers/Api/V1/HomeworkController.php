<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Academic\Homework;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeworkController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $homeworks = Homework::with('subject', 'class', 'section', 'teacher')->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))->when($request->section_id, fn($q) => $q->where('section_id', $request->section_id))->when($request->subject_id, fn($q) => $q->where('subject_id', $request->subject_id))->when($request->date, fn($q) => $q->whereDate('homework_date', $request->date))->when($request->status, fn($q) => $q->where('status', $request->status))->orderBy('homework_date', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($homeworks, 'Homework records retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subject_id' => 'required|integer|exists:subjects,id',
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'homework_date' => 'required|date',
            'submission_date' => 'required|date|after_or_equal:homework_date',
            'max_marks' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:pending,completed,cancelled',
            'attachments' => 'nullable|array',
        ]);

        $homework = Homework::create(array_merge($validated, ['created_by' => $request->user()->id]));
        return $this->createdResponse($homework->load('subject', 'class', 'teacher'), 'Homework created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(Homework::with('subject', 'class', 'section', 'teacher')->findOrFail($id), 'Homework retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $homework = Homework::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:5000',
            'submission_date' => 'sometimes|date|after_or_equal:homework_date',
            'max_marks' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:pending,completed,cancelled',
        ]);
        $homework->update($validated);
        return $this->updatedResponse($homework->fresh()->load('subject', 'class'), 'Homework updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Homework::findOrFail($id)->delete();
        return $this->deletedResponse('Homework deleted');
    }
}
