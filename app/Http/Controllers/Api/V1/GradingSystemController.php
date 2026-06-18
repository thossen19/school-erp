<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Assessment\GradingSystem;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GradingSystemController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $systems = GradingSystem::with('grades')->when($request->is_active, fn($q) => $q->where('is_active', $request->boolean('is_active')))->orderBy('name')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($systems, 'Grading systems retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:grading_systems,code',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'grades' => 'required|array|min:1',
            'grades.*.grade' => 'required|string|max:10',
            'grades.*.min_percentage' => 'required|numeric|min:0|max:100',
            'grades.*.max_percentage' => 'required|numeric|min:0|max:100|gte:grades.*.min_percentage',
            'grades.*.grade_point' => 'nullable|numeric|min:0',
            'grades.*.description' => 'nullable|string|max:255',
        ]);

        $system = GradingSystem::create($validated);
        if ($request->has('grades')) {
            $system->grades()->createMany($request->grades);
        }

        return $this->createdResponse($system->load('grades'), 'Grading system created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(GradingSystem::with('grades')->findOrFail($id), 'Grading system retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $system = GradingSystem::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:50|unique:grading_systems,code,' . $id,
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);
        $system->update($validated);

        if ($request->has('grades')) {
            $system->grades()->delete();
            $system->grades()->createMany($request->grades);
        }

        return $this->updatedResponse($system->fresh()->load('grades'), 'Grading system updated');
    }

    public function destroy(int $id): JsonResponse
    {
        GradingSystem::findOrFail($id)->delete();
        return $this->deletedResponse('Grading system deleted');
    }
}
