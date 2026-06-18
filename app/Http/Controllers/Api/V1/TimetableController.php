<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Timetable\StoreTimetableRequest;
use App\Models\Timetable\Timetable;
use App\Services\Timetable\TimetableService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    use ApiResponseTrait;

    protected TimetableService $timetableService;

    public function __construct(TimetableService $timetableService)
    {
        $this->timetableService = $timetableService;
    }

    public function index(Request $request): JsonResponse
    {
        $timetables = Timetable::with('class', 'section')->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))->when($request->section_id, fn($q) => $q->where('section_id', $request->section_id))->when($request->is_active, fn($q) => $q->where('is_active', $request->boolean('is_active')))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($timetables, 'Timetables retrieved');
    }

    public function store(StoreTimetableRequest $request): JsonResponse
    {
        $timetable = $this->timetableService->createTimetable($request->validated());
        return $this->createdResponse($timetable->load('class', 'section', 'periods'), 'Timetable created');
    }

    public function show(int $id): JsonResponse
    {
        $timetable = Timetable::with('class', 'section', 'periods.subject', 'periods.teacher', 'periods.room')->findOrFail($id);
        return $this->successResponse($timetable, 'Timetable retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $timetable = Timetable::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'effective_from' => 'sometimes|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'is_active' => 'boolean',
        ]);
        $timetable->update($validated);
        return $this->updatedResponse($timetable->fresh()->load('class', 'section', 'periods'), 'Timetable updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Timetable::findOrFail($id)->delete();
        return $this->deletedResponse('Timetable deleted');
    }

    public function generateAI(Request $request): JsonResponse
    {
        $request->validate([
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'academic_year_id' => 'required|integer|exists:academic_years,id',
        ]);

        $timetable = $this->timetableService->generateAITimetable($request->class_id, $request->section_id, $request->academic_year_id);
        return $this->successResponse($timetable, 'AI timetable generated');
    }

    public function detectConflicts(Request $request): JsonResponse
    {
        $request->validate(['timetable_id' => 'required|integer|exists:timetables,id']);
        $conflicts = $this->timetableService->detectConflicts($request->timetable_id);
        return $this->successResponse($conflicts, 'Conflicts detected');
    }

    public function getClassTimetable(int $classId, ?int $sectionId = null): JsonResponse
    {
        $timetable = $this->timetableService->getClassTimetable($classId, $sectionId);
        return $this->successResponse($timetable, 'Class timetable retrieved');
    }

    public function getTeacherTimetable(int $teacherId, Request $request): JsonResponse
    {
        $timetable = $this->timetableService->getTeacherTimetable($teacherId);
        return $this->successResponse($timetable, 'Teacher timetable retrieved');
    }
}
