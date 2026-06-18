<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Hostel\HostelRoom;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HostelRoomController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $rooms = HostelRoom::with('hostel')->when($request->hostel_id, fn($q) => $q->where('hostel_id', $request->hostel_id))->when($request->room_type, fn($q) => $q->where('room_type', $request->room_type))->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->search, fn($q) => $q->where('room_no', 'like', "%{$request->search}%"))->orderBy('room_no')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($rooms, 'Hostel rooms retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'hostel_id' => 'required|integer|exists:hostels,id',
            'room_no' => 'required|string|max:50|unique:hostel_rooms,room_no',
            'floor' => 'nullable|string|max:50',
            'room_type' => 'required|string|in:single,double,triple,dormitory',
            'capacity' => 'required|integer|min:1',
            'occupied_beds' => 'nullable|integer|min:0|lte:capacity',
            'rent_amount' => 'nullable|numeric|min:0',
            'facilities' => 'nullable|array',
            'status' => 'nullable|string|in:available,occupied,maintenance,reserved',
            'notes' => 'nullable|string|max:500',
        ]);

        $room = HostelRoom::create($validated);
        return $this->createdResponse($room->load('hostel'), 'Room created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            HostelRoom::with('hostel', 'beds', 'allocations.student')->findOrFail($id),
            'Room retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $room = HostelRoom::findOrFail($id);
        $validated = $request->validate([
            'room_no' => 'sometimes|string|max:50|unique:hostel_rooms,room_no,' . $id,
            'floor' => 'nullable|string|max:50',
            'room_type' => 'sometimes|string|in:single,double,triple,dormitory',
            'capacity' => 'sometimes|integer|min:1',
            'rent_amount' => 'nullable|numeric|min:0',
            'facilities' => 'nullable|array',
            'status' => 'nullable|string|in:available,occupied,maintenance,reserved',
            'notes' => 'nullable|string|max:500',
        ]);
        $room->update($validated);
        return $this->updatedResponse($room->fresh()->load('hostel'), 'Room updated');
    }

    public function destroy(int $id): JsonResponse
    {
        HostelRoom::findOrFail($id)->delete();
        return $this->deletedResponse('Room deleted');
    }

    public function getBeds(int $roomId): JsonResponse
    {
        $room = HostelRoom::with('beds', 'beds.allocation')->findOrFail($roomId);
        return $this->successResponse($room->beds, 'Beds retrieved');
    }
}
