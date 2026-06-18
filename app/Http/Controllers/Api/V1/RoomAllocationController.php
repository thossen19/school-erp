<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Timetable\RoomAllocation;
use App\Services\Timetable\RoomAllocationService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoomAllocationController extends Controller
{
    use ApiResponseTrait;

    protected RoomAllocationService $roomAllocationService;

    public function __construct(RoomAllocationService $roomAllocationService)
    {
        $this->roomAllocationService = $roomAllocationService;
    }

    public function index(Request $request): JsonResponse
    {
        $rooms = RoomAllocation::with('branch')->when($request->type, fn($q) => $q->where('type', $request->type))->when($request->is_available, fn($q) => $q->where('is_available', $request->boolean('is_available')))->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))->orderBy('name')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($rooms, 'Room allocations retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:room_allocations,code',
            'type' => 'required|string|in:classroom,laboratory,library,office,auditorium,other',
            'capacity' => 'required|integer|min:1',
            'floor' => 'nullable|string|max:50',
            'building' => 'nullable|string|max:255',
            'facilities' => 'nullable|array',
            'is_available' => 'boolean',
            'branch_id' => 'nullable|integer|exists:branches,id',
            'description' => 'nullable|string|max:500',
        ]);

        $room = RoomAllocation::create($validated);
        return $this->createdResponse($room, 'Room created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(RoomAllocation::with('branch')->findOrFail($id), 'Room retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $room = RoomAllocation::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:50|unique:room_allocations,code,' . $id,
            'type' => 'sometimes|string|in:classroom,laboratory,library,office,auditorium,other',
            'capacity' => 'sometimes|integer|min:1',
            'floor' => 'nullable|string|max:50',
            'building' => 'nullable|string|max:255',
            'facilities' => 'nullable|array',
            'is_available' => 'boolean',
            'branch_id' => 'nullable|integer|exists:branches,id',
            'description' => 'nullable|string|max:500',
        ]);
        $room->update($validated);
        return $this->updatedResponse($room->fresh(), 'Room updated');
    }

    public function destroy(int $id): JsonResponse
    {
        RoomAllocation::findOrFail($id)->delete();
        return $this->deletedResponse('Room deleted');
    }

    public function checkAvailability(Request $request): JsonResponse
    {
        $request->validate([
            'day' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_id' => 'nullable|integer|exists:room_allocations,id',
        ]);

        $rooms = $this->roomAllocationService->checkAvailability($request->day, $request->start_time, $request->end_time, $request->room_id);
        return $this->successResponse($rooms, 'Room availability');
    }
}
