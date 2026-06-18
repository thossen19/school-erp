<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Fee\FeeDiscount;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeeDiscountController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $discounts = FeeDiscount::withCount('students')->when($request->category_id, fn($q) => $q->where('fee_category_id', $request->category_id))->when($request->type, fn($q) => $q->where('type', $request->type))->when($request->is_active, fn($q) => $q->where('is_active', $request->boolean('is_active')))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($discounts, 'Fee discounts retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:fee_discounts,code',
            'fee_category_id' => 'required|integer|exists:fee_categories,id',
            'type' => 'required|string|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after_or_equal:valid_from',
        ]);

        $discount = FeeDiscount::create($validated);
        return $this->createdResponse($discount, 'Fee discount created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(FeeDiscount::with('category', 'students')->findOrFail($id), 'Fee discount retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $discount = FeeDiscount::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:50|unique:fee_discounts,code,' . $id,
            'fee_category_id' => 'sometimes|integer|exists:fee_categories,id',
            'type' => 'sometimes|string|in:percentage,fixed',
            'value' => 'sometimes|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after_or_equal:valid_from',
        ]);
        $discount->update($validated);
        return $this->updatedResponse($discount->fresh(), 'Fee discount updated');
    }

    public function destroy(int $id): JsonResponse
    {
        FeeDiscount::findOrFail($id)->delete();
        return $this->deletedResponse('Fee discount deleted');
    }

    public function applyToStudent(Request $request, int $discountId): JsonResponse
    {
        $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'academic_year_id' => 'required|integer|exists:academic_years,id',
        ]);

        $discount = FeeDiscount::findOrFail($discountId);
        $discount->students()->syncWithoutDetaching([
            $request->student_id => ['academic_year_id' => $request->academic_year_id],
        ]);

        return $this->successResponse($discount->load('students'), 'Discount applied to student');
    }
}
