<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Transport\TransportDriver;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransportDriverController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $drivers = TransportDriver::withCount('routes')->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")->orWhere('license_no', 'like', "%{$request->search}%")->orWhere('phone', 'like', "%{$request->search}%"))->orderBy('name')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($drivers, 'Transport drivers retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'license_no' => 'required|string|max:50|unique:transport_drivers,license_no',
            'license_expiry' => 'nullable|date|after:today',
            'license_type' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date|before:today',
            'emergency_contact' => 'nullable|string|max:20',
            'joining_date' => 'nullable|date',
            'status' => 'nullable|string|in:active,inactive,suspended',
            'notes' => 'nullable|string|max:500',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $driver = TransportDriver::create($validated);

        if ($request->hasFile('photo')) {
            $driver->update(['photo' => $request->file('photo')->store('transport/drivers', 'public')]);
        }

        return $this->createdResponse($driver, 'Driver created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            TransportDriver::with('routes', 'vehicle')->findOrFail($id),
            'Driver retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $driver = TransportDriver::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'license_no' => 'sometimes|string|max:50|unique:transport_drivers,license_no,' . $id,
            'license_expiry' => 'nullable|date|after:today',
            'status' => 'nullable|string|in:active,inactive,suspended',
            'notes' => 'nullable|string|max:500',
        ]);
        $driver->update($validated);
        return $this->updatedResponse($driver->fresh(), 'Driver updated');
    }

    public function destroy(int $id): JsonResponse
    {
        TransportDriver::findOrFail($id)->delete();
        return $this->deletedResponse('Driver deleted');
    }
}
