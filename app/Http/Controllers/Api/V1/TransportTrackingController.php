<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Transport\TransportTracking;
use App\Services\Transport\TrackingService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransportTrackingController extends Controller
{
    use ApiResponseTrait;

    protected TrackingService $trackingService;

    public function __construct(TrackingService $trackingService)
    {
        $this->trackingService = $trackingService;
    }

    public function updateLocation(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|integer|exists:transport_vehicles,id',
            'latitude' => 'required|numeric|min:-90|max:90',
            'longitude' => 'required|numeric|min:-180|max:180',
            'speed' => 'nullable|numeric|min:0',
            'heading' => 'nullable|numeric|min:0|max:360',
            'accuracy' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|in:on_route,stopped,offline',
        ]);

        $tracking = TransportTracking::create(array_merge($validated, ['tracked_at' => now()]));
        return $this->createdResponse($tracking, 'Location updated');
    }

    public function getVehicleLocation(int $vehicleId): JsonResponse
    {
        $location = TransportTracking::where('vehicle_id', $vehicleId)->orderBy('tracked_at', 'desc')->first();

        if (!$location) {
            return $this->notFoundResponse('No location data found for this vehicle');
        }

        return $this->successResponse($location, 'Vehicle location');
    }

    public function getRouteHistory(Request $request, int $vehicleId): JsonResponse
    {
        $request->validate([
            'date' => 'required|date',
            'from_time' => 'nullable|date_format:H:i',
            'to_time' => 'nullable|date_format:H:i|after_or_equal:from_time',
        ]);

        $history = TransportTracking::where('vehicle_id', $vehicleId)->whereDate('tracked_at', $request->date)->when($request->from_time, fn($q) => $q->whereTime('tracked_at', '>=', $request->from_time))->when($request->to_time, fn($q) => $q->whereTime('tracked_at', '<=', $request->to_time))->orderBy('tracked_at', 'asc')->get();

        return $this->successResponse($history, 'Route history');
    }
}
