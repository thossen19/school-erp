<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\StoreClassRequest;
use App\Models\Academic\ClassModel;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $classes = ClassModel::withCount('sections', 'students', 'subjects')->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))->when($request->is_active, fn($q) => $q->where('is_active', $request->boolean('is_active')))->orderBy('numeric_value')->orderBy('name')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($classes, 'Classes retrieved');
    }

    public function store(StoreClassRequest $request): JsonResponse
    {
        $class = ClassModel::create($request->validated());
        return $this->createdResponse($class, 'Class created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            ClassModel::with('sections', 'subjects.subject', 'feeStructures')->withCount('students')->findOrFail($id),
            'Class retrieved'
        );
    }

    public function update(StoreClassRequest $request, int $id): JsonResponse
    {
        $class = ClassModel::findOrFail($id);
        $class->update($request->validated());
        return $this->updatedResponse($class->fresh(), 'Class updated');
    }

    public function destroy(int $id): JsonResponse
    {
        ClassModel::findOrFail($id)->delete();
        return $this->deletedResponse('Class deleted');
    }

    public function getSections(int $classId): JsonResponse
    {
        $class = ClassModel::with('sections')->findOrFail($classId);
        return $this->successResponse($class->sections, 'Sections retrieved');
    }
}
