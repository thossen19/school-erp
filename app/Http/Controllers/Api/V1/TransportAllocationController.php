<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Transport\TransportAllocation;
use App\Services\Transport\TransportService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransportAllocationController extends Controller
{
    use ApiResponseTrait;

    protected TransportService $transportService;

    public function __construct(TransportService $transportService)
    {
        $this->transportService = $transportService;
    }

    public function index(Request $request): JsonResponse
    {
        $allocations = TransportAllocation::with('student:id,first_name,last_name', 'route', 'stop')->when($request->route_id, fn($q) => $q->where('transport_route_id', $request->route_id))->when($request->student_id, fn($q) => $q->where('student_id', $request->student_id))->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->academic_year_id, fn($q) => $q->where('academic_year_id', $request->academic_year_id))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($allocations, 'Transport allocations retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'transport_route_id' => 'required|integer|exists:transport_routes,id',
            'route_stop_id' => 'required|integer|exists:transport_route_stops,id',
            'academic_year_id' => 'required|integer|exists:academic_years,id',
            'pickup_point' => 'nullable|string|max:255',
            'drop_point' => 'nullable|string|max:255',
            'pickup_time' => 'nullable|date_format:H:i',
            'drop_time' => 'nullable|date_format:H:i',
            'fee_amount' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|in:active,inactive,suspended',
        ]);

        $allocation = TransportAllocation::create($validated);
        return $this->createdResponse($allocation->load('student:id,first_name,last_name', 'route', 'stop'), 'Transport allocation created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            TransportAllocation::with('student', 'route', 'stop', 'route.stops')->findOrFail($id),
            'Transport allocation retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $allocation = TransportAllocation::findOrFail($id);
        $validated = $request->validate([
            'transport_route_id' => 'sometimes|integer|exists:transport_routes,id',
            'route_stop_id' => 'sometimes|integer|exists:transport_route_stops,id',
            'pickup_point' => 'nullable|string|max:255',
            'drop_point' => 'nullable|string|max:255',
            'pickup_time' => 'nullable|date_format:H:i',
            'drop_time' => 'nullable|date_format:H:i',
            'status' => 'nullable|string|in:active,inactive,suspended',
        ]);
        $allocation->update($validated);
        return $this->updatedResponse($allocation->fresh()->load('student', 'route'), 'Transport allocation updated');
    }

    public function destroy(int $id): JsonResponse
    {
        TransportAllocation::findOrFail($id)->delete();
        return $this->deletedResponse('Transport allocation deleted');
    }

    public function getByStudent(int $studentId): JsonResponse
    {
        $allocations = TransportAllocation::with('route', 'stop')->where('student_id', $studentId)->where('status', 'active')->get();
        return $this->successResponse($allocations, 'Transport allocations by student');
    }
}
