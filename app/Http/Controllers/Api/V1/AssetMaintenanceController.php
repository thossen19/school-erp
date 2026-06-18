<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Asset\AssetMaintenance;
use App\Services\Asset\AssetMaintenanceService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssetMaintenanceController extends Controller
{
    use ApiResponseTrait;

    protected AssetMaintenanceService $maintenanceService;

    public function __construct(AssetMaintenanceService $maintenanceService)
    {
        $this->maintenanceService = $maintenanceService;
    }

    public function index(Request $request): JsonResponse
    {
        $maintenance = AssetMaintenance::with('asset')->when($request->asset_id, fn($q) => $q->where('asset_id', $request->asset_id))->when($request->type, fn($q) => $q->where('maintenance_type', $request->type))->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->priority, fn($q) => $q->where('priority', $request->priority))->when($request->date_from, fn($q) => $q->whereDate('start_date', '>=', $request->date_from))->when($request->date_to, fn($q) => $q->whereDate('start_date', '<=', $request->date_to))->orderBy('start_date', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($maintenance, 'Asset maintenance records retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'asset_id' => 'required|integer|exists:assets,id',
            'maintenance_type' => 'required|string|in:routine,repair,emergency,upgrade',
            'description' => 'required|string|max:2000',
            'start_date' => 'required|date',
            'expected_end_date' => 'nullable|date|after_or_equal:start_date',
            'actual_end_date' => 'nullable|date|after_or_equal:start_date',
            'cost' => 'nullable|numeric|min:0',
            'vendor' => 'nullable|string|max:255',
            'vendor_contact' => 'nullable|string|max:20',
            'priority' => 'nullable|string|in:low,medium,high,urgent',
            'status' => 'nullable|string|in:pending,in_progress,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        $record = AssetMaintenance::create($validated);
        return $this->createdResponse($record->load('asset'), 'Maintenance record created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(AssetMaintenance::with('asset')->findOrFail($id), 'Maintenance record retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $record = AssetMaintenance::findOrFail($id);
        $validated = $request->validate([
            'description' => 'sometimes|string|max:2000',
            'expected_end_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date',
            'cost' => 'nullable|numeric|min:0',
            'vendor' => 'nullable|string|max:255',
            'priority' => 'nullable|string|in:low,medium,high,urgent',
            'status' => 'nullable|string|in:pending,in_progress,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);
        $record->update($validated);
        return $this->updatedResponse($record->fresh()->load('asset'), 'Maintenance record updated');
    }

    public function destroy(int $id): JsonResponse
    {
        AssetMaintenance::findOrFail($id)->delete();
        return $this->deletedResponse('Maintenance record deleted');
    }
}
