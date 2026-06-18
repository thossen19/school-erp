<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Transport\TransportVehicle;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransportVehicleController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $vehicles = TransportVehicle::withCount('routes')->when($request->type, fn($q) => $q->where('vehicle_type', $request->type))->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->search, fn($q) => $q->where('registration_no', 'like', "%{$request->search}%")->orWhere('model', 'like', "%{$request->search}%"))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($vehicles, 'Transport vehicles retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'vehicle_type' => 'required|string|in:bus,van,car,auto,truck,other',
            'registration_no' => 'required|string|max:50|unique:transport_vehicles,registration_no',
            'model' => 'required|string|max:255',
            'make' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1990|max:' . date('Y'),
            'capacity' => 'required|integer|min:1',
            'color' => 'nullable|string|max:50',
            'chassis_no' => 'nullable|string|max:100',
            'engine_no' => 'nullable|string|max:100',
            'insurance_expiry' => 'nullable|date',
            'fitness_expiry' => 'nullable|date',
            'pollution_expiry' => 'nullable|date',
            'tax_expiry' => 'nullable|date',
            'fuel_type' => 'nullable|string|in:petrol,diesel,cng,electric',
            'status' => 'nullable|string|in:active,inactive,maintenance,retired',
            'notes' => 'nullable|string|max:500',
        ]);

        $vehicle = TransportVehicle::create($validated);
        return $this->createdResponse($vehicle, 'Vehicle created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            TransportVehicle::with('routes', 'driver', 'trackings')->findOrFail($id),
            'Vehicle retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $vehicle = TransportVehicle::findOrFail($id);
        $validated = $request->validate([
            'vehicle_type' => 'sometimes|string|in:bus,van,car,auto,truck,other',
            'registration_no' => 'sometimes|string|max:50|unique:transport_vehicles,registration_no,' . $id,
            'model' => 'sometimes|string|max:255',
            'capacity' => 'sometimes|integer|min:1',
            'insurance_expiry' => 'nullable|date',
            'fitness_expiry' => 'nullable|date',
            'status' => 'nullable|string|in:active,inactive,maintenance,retired',
        ]);
        $vehicle->update($validated);
        return $this->updatedResponse($vehicle->fresh(), 'Vehicle updated');
    }

    public function destroy(int $id): JsonResponse
    {
        TransportVehicle::findOrFail($id)->delete();
        return $this->deletedResponse('Vehicle deleted');
    }
}
