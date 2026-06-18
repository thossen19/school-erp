<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Fee\FeeStructure;
use App\Services\Fee\FeeService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeeStructureController extends Controller
{
    use ApiResponseTrait;

    protected FeeService $feeService;

    public function __construct(FeeService $feeService)
    {
        $this->feeService = $feeService;
    }

    public function index(Request $request): JsonResponse
    {
        $structures = FeeStructure::with('category', 'class', 'installments')->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))->when($request->category_id, fn($q) => $q->where('fee_category_id', $request->category_id))->when($request->academic_year_id, fn($q) => $q->where('academic_year_id', $request->academic_year_id))->when($request->is_active, fn($q) => $q->where('is_active', $request->boolean('is_active')))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($structures, 'Fee structures retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fee_category_id' => 'required|integer|exists:fee_categories,id',
            'class_id' => 'required|integer|exists:classes,id',
            'academic_year_id' => 'required|integer|exists:academic_years,id',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'is_optional' => 'boolean',
            'is_active' => 'boolean',
            'description' => 'nullable|string|max:500',
            'installments' => 'nullable|array',
            'installments.*.name' => 'required|string|max:255',
            'installments.*.due_date' => 'required|date',
            'installments.*.amount' => 'required|numeric|min:0',
            'installments.*.late_fee' => 'nullable|numeric|min:0',
        ]);

        $structure = $this->feeService->createFeeStructure($validated);
        return $this->createdResponse($structure->load('category', 'class', 'installments'), 'Fee structure created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(FeeStructure::with('category', 'class', 'installments')->findOrFail($id), 'Fee structure retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $structure = FeeStructure::findOrFail($id);
        $validated = $request->validate([
            'fee_category_id' => 'sometimes|integer|exists:fee_categories,id',
            'class_id' => 'sometimes|integer|exists:classes,id',
            'amount' => 'sometimes|numeric|min:0',
            'is_optional' => 'boolean',
            'is_active' => 'boolean',
            'description' => 'nullable|string|max:500',
        ]);
        $structure->update($validated);
        return $this->updatedResponse($structure->fresh()->load('category', 'class', 'installments'), 'Fee structure updated');
    }

    public function destroy(int $id): JsonResponse
    {
        FeeStructure::findOrFail($id)->delete();
        return $this->deletedResponse('Fee structure deleted');
    }

    public function getByClass(int $classId, Request $request): JsonResponse
    {
        $structures = FeeStructure::with('category', 'installments')->where('class_id', $classId)->when($request->academic_year_id, fn($q) => $q->where('academic_year_id', $request->academic_year_id))->when($request->category_id, fn($q) => $q->where('fee_category_id', $request->category_id))->get();
        return $this->successResponse($structures, 'Fee structures by class');
    }
}
