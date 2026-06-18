<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Hostel\HostelAllocation;
use App\Services\Hostel\HostelAllocationService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HostelAllocationController extends Controller
{
    use ApiResponseTrait;

    protected HostelAllocationService $allocationService;

    public function __construct(HostelAllocationService $allocationService)
    {
        $this->allocationService = $allocationService;
    }

    public function index(Request $request): JsonResponse
    {
        $allocations = HostelAllocation::with('student:id,first_name,last_name', 'room', 'bed')->when($request->hostel_id, fn($q) => $q->whereHas('room', fn($q) => $q->where('hostel_id', $request->hostel_id)))->when($request->room_id, fn($q) => $q->where('hostel_room_id', $request->room_id))->when($request->student_id, fn($q) => $q->where('student_id', $request->student_id))->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->academic_year_id, fn($q) => $q->where('academic_year_id', $request->academic_year_id))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($allocations, 'Hostel allocations retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'hostel_room_id' => 'required|integer|exists:hostel_rooms,id',
            'hostel_bed_id' => 'nullable|integer|exists:hostel_beds,id',
            'academic_year_id' => 'required|integer|exists:academic_years,id',
            'allocation_date' => 'required|date',
            'expected_checkout_date' => 'nullable|date|after:allocation_date',
            'monthly_rent' => 'nullable|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|in:active,checked_out,transferred',
        ]);

        $allocation = $this->allocationService->allocateRoom($request->validated());
        return $this->createdResponse($allocation->load('student:id,first_name,last_name', 'room', 'bed'), 'Room allocated');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            HostelAllocation::with('student', 'room.hostel', 'bed')->findOrFail($id),
            'Hostel allocation retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $allocation = HostelAllocation::findOrFail($id);
        $validated = $request->validate([
            'hostel_room_id' => 'sometimes|integer|exists:hostel_rooms,id',
            'hostel_bed_id' => 'nullable|integer|exists:hostel_beds,id',
            'expected_checkout_date' => 'nullable|date|after:allocation_date',
            'monthly_rent' => 'nullable|numeric|min:0',
        ]);
        $allocation->update($validated);
        return $this->updatedResponse($allocation->fresh()->load('student', 'room', 'bed'), 'Allocation updated');
    }

    public function destroy(int $id): JsonResponse
    {
        HostelAllocation::findOrFail($id)->delete();
        return $this->deletedResponse('Allocation deleted');
    }

    public function checkIn(Request $request, int $id): JsonResponse
    {
        $allocation = HostelAllocation::findOrFail($id);
        $allocation->update(['check_in_date' => now(), 'status' => 'active']);
        return $this->successResponse($allocation->load('student', 'room'), 'Checked in');
    }

    public function checkOut(Request $request, int $id): JsonResponse
    {
        $request->validate(['check_out_date' => 'required|date', 'remarks' => 'nullable|string|max:500']);
        $allocation = HostelAllocation::findOrFail($id);
        $allocation->update([
            'check_out_date' => $request->check_out_date,
            'status' => 'checked_out',
            'remarks' => $request->remarks,
        ]);
        return $this->successResponse($allocation, 'Checked out');
    }
}
