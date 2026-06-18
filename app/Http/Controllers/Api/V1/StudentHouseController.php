<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentHouse;
use App\Services\Student\StudentHouseService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentHouseController extends Controller
{
    use ApiResponseTrait;

    protected StudentHouseService $houseService;

    public function __construct(StudentHouseService $houseService)
    {
        $this->houseService = $houseService;
    }

    public function index(Request $request): JsonResponse
    {
        $houses = StudentHouse::withCount('students')->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))->orderBy('name')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($houses, 'Student houses retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:student_houses,code',
            'motto' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
            'captain_id' => 'nullable|integer|exists:students,id',
            'vice_captain_id' => 'nullable|integer|exists:students,id',
            'is_active' => 'boolean',
        ]);

        $house = StudentHouse::create($validated);
        return $this->createdResponse($house, 'Student house created');
    }

    public function show(int $id): JsonResponse
    {
        $house = StudentHouse::with(['students', 'students.class', 'captain', 'viceCaptain'])->withCount('students')->findOrFail($id);
        return $this->successResponse($house, 'Student house retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $house = StudentHouse::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'code' => 'sometimes|string|max:20|unique:student_houses,code,' . $id,
            'motto' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
            'captain_id' => 'nullable|integer|exists:students,id',
            'vice_captain_id' => 'nullable|integer|exists:students,id',
            'is_active' => 'boolean',
        ]);
        $house->update($validated);
        return $this->updatedResponse($house->fresh(), 'Student house updated');
    }

    public function destroy(int $id): JsonResponse
    {
        StudentHouse::findOrFail($id)->delete();
        return $this->deletedResponse('Student house deleted');
    }

    public function assignStudents(Request $request, int $houseId): JsonResponse
    {
        $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'integer|exists:students,id',
        ]);

        $this->houseService->assignStudentsToHouse($houseId, $request->student_ids);
        return $this->successResponse(null, 'Students assigned to house');
    }

    public function getReport(int $id): JsonResponse
    {
        $report = $this->houseService->getHouseReport($id);
        return $this->successResponse($report, 'House report generated');
    }
}
