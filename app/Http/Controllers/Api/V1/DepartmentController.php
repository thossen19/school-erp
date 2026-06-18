<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Hr\Department;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $departments = Department::withCount('employees')->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))->orderBy('name')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($departments, 'Departments retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments,code',
            'description' => 'nullable|string|max:500',
            'head_of_department' => 'nullable|integer|exists:employees,id',
            'is_active' => 'boolean',
        ]);

        $department = Department::create($validated);
        return $this->createdResponse($department, 'Department created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            Department::with('head')->withCount('employees')->findOrFail($id),
            'Department retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $department = Department::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:50|unique:departments,code,' . $id,
            'description' => 'nullable|string|max:500',
            'head_of_department' => 'nullable|integer|exists:employees,id',
            'is_active' => 'boolean',
        ]);
        $department->update($validated);
        return $this->updatedResponse($department->fresh(), 'Department updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Department::findOrFail($id)->delete();
        return $this->deletedResponse('Department deleted');
    }
}
