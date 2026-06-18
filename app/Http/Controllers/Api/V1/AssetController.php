<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Asset\StoreAssetRequest;
use App\Models\Asset\Asset;
use App\Services\Asset\AssetService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    use ApiResponseTrait;

    protected AssetService $assetService;

    public function __construct(AssetService $assetService)
    {
        $this->assetService = $assetService;
    }

    public function index(Request $request): JsonResponse
    {
        $assets = Asset::with('category')->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->location, fn($q) => $q->where('location', 'like', "%{$request->location}%"))->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")->orWhere('asset_no', 'like', "%{$request->search}%"))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($assets, 'Assets retrieved');
    }

    public function store(StoreAssetRequest $request): JsonResponse
    {
        $asset = $this->assetService->createAsset($request->validated());
        return $this->createdResponse($asset->load('category'), 'Asset created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            Asset::with('category', 'allocations.user', 'maintenanceRecords', 'depreciations', 'audits')->findOrFail($id),
            'Asset retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $asset = Asset::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'asset_no' => 'sometimes|string|max:50|unique:assets,asset_no,' . $id,
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|string|in:available,allocated,under_maintenance,disposed',
        ]);
        $asset->update($validated);
        return $this->updatedResponse($asset->fresh()->load('category'), 'Asset updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Asset::findOrFail($id)->delete();
        return $this->deletedResponse('Asset deleted');
    }

    public function allocate(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'allocated_to' => 'required|integer|exists:users,id',
            'allocated_date' => 'required|date',
            'expected_return_date' => 'nullable|date|after:allocated_date',
            'purpose' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        $allocation = $this->assetService->allocateAsset($id, $request->validated());
        return $this->createdResponse($allocation, 'Asset allocated');
    }

    public function maintain(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'maintenance_type' => 'required|string|in:routine,repair,emergency,upgrade',
            'description' => 'required|string|max:2000',
            'start_date' => 'required|date',
            'expected_end_date' => 'nullable|date|after_or_equal:start_date',
            'cost' => 'nullable|numeric|min:0',
            'vendor' => 'nullable|string|max:255',
            'priority' => 'nullable|string|in:low,medium,high,urgent',
        ]);

        $maintenance = $this->assetService->scheduleMaintenance($id, $validated);
        return $this->createdResponse($maintenance, 'Maintenance scheduled');
    }

    public function depreciate(int $id): JsonResponse
    {
        $depreciation = $this->assetService->calculateDepreciation($id);
        return $this->successResponse($depreciation, 'Depreciation calculated');
    }

    public function audit(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'audit_date' => 'required|date',
            'condition' => 'required|string|in:excellent,good,fair,poor,damaged',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $audit = $this->assetService->performAudit($id, $request->validated());
        return $this->createdResponse($audit, 'Asset audit completed');
    }
}
