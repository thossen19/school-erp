<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Payroll\SalaryStructure;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SalaryStructureController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $structures = SalaryStructure::with('employee:id,first_name,last_name,employee_no')->when($request->employee_id, fn($q) => $q->where('employee_id', $request->employee_id))->when($request->is_active, fn($q) => $q->where('is_active', $request->boolean('is_active')))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($structures, 'Salary structures retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|integer|exists:employees,id',
            'basic_salary' => 'required|numeric|min:0',
            'components' => 'required|array',
            'components.*.name' => 'required|string|max:255',
            'components.*.type' => 'required|string|in:earning,deduction',
            'components.*.amount' => 'required|numeric|min:0',
            'components.*.is_percentage' => 'boolean',
            'components.*.percentage_value' => 'nullable|numeric|min:0|max:100',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'is_active' => 'boolean',
        ]);

        $structure = SalaryStructure::create($validated);
        if ($request->has('components')) {
            $structure->components()->createMany($request->components);
        }

        return $this->createdResponse($structure->load('components', 'employee'), 'Salary structure created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            SalaryStructure::with('employee', 'components')->findOrFail($id),
            'Salary structure retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $structure = SalaryStructure::findOrFail($id);
        $validated = $request->validate([
            'basic_salary' => 'sometimes|numeric|min:0',
            'effective_from' => 'sometimes|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'is_active' => 'boolean',
        ]);
        $structure->update($validated);

        if ($request->has('components')) {
            $structure->components()->delete();
            $structure->components()->createMany($request->components);
        }

        return $this->updatedResponse($structure->fresh()->load('components', 'employee'), 'Salary structure updated');
    }

    public function destroy(int $id): JsonResponse
    {
        SalaryStructure::findOrFail($id)->delete();
        return $this->deletedResponse('Salary structure deleted');
    }
}
