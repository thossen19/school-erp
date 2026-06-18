<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Fee\FeeCategory;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeeCategoryController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $categories = FeeCategory::withCount('feeStructures')->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))->orderBy('name')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($categories, 'Fee categories retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:fee_categories,code',
            'description' => 'nullable|string|max:500',
            'is_recurring' => 'boolean',
            'frequency' => 'nullable|string|in:monthly,quarterly,half_yearly,yearly,one_time',
            'is_active' => 'boolean',
        ]);

        $category = FeeCategory::create($validated);
        return $this->createdResponse($category, 'Fee category created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(FeeCategory::with('feeStructures')->findOrFail($id), 'Fee category retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $category = FeeCategory::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:50|unique:fee_categories,code,' . $id,
            'description' => 'nullable|string|max:500',
            'is_recurring' => 'boolean',
            'frequency' => 'nullable|string|in:monthly,quarterly,half_yearly,yearly,one_time',
            'is_active' => 'boolean',
        ]);
        $category->update($validated);
        return $this->updatedResponse($category->fresh(), 'Fee category updated');
    }

    public function destroy(int $id): JsonResponse
    {
        FeeCategory::findOrFail($id)->delete();
        return $this->deletedResponse('Fee category deleted');
    }
}
