<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Asset\AssetCategory;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssetCategoryController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $categories = AssetCategory::withCount('assets')->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))->orderBy('name')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($categories, 'Asset categories retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:asset_categories,code',
            'description' => 'nullable|string|max:500',
            'depreciation_method' => 'nullable|string|in:straight_line,declining_balance,sum_of_years,units_of_production',
            'useful_life_years' => 'nullable|integer|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        $category = AssetCategory::create($validated);
        return $this->createdResponse($category, 'Asset category created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            AssetCategory::with('assets')->withCount('assets')->findOrFail($id),
            'Asset category retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $category = AssetCategory::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:50|unique:asset_categories,code,' . $id,
            'description' => 'nullable|string|max:500',
            'depreciation_method' => 'nullable|string|in:straight_line,declining_balance,sum_of_years,units_of_production',
            'useful_life_years' => 'nullable|integer|min:0|max:100',
            'is_active' => 'boolean',
        ]);
        $category->update($validated);
        return $this->updatedResponse($category->fresh(), 'Asset category updated');
    }

    public function destroy(int $id): JsonResponse
    {
        AssetCategory::findOrFail($id)->delete();
        return $this->deletedResponse('Asset category deleted');
    }
}
