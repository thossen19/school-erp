<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hostel\StoreHostelRequest;
use App\Models\Hostel\Hostel;
use App\Services\Hostel\HostelService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HostelController extends Controller
{
    use ApiResponseTrait;

    protected HostelService $hostelService;

    public function __construct(HostelService $hostelService)
    {
        $this->hostelService = $hostelService;
    }

    public function index(Request $request): JsonResponse
    {
        $hostels = Hostel::withCount('rooms', 'allocations')->when($request->type, fn($q) => $q->where('type', $request->type))->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))->orderBy('name')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($hostels, 'Hostels retrieved');
    }

    public function store(StoreHostelRequest $request): JsonResponse
    {
        $hostel = Hostel::create($request->validated());
        return $this->createdResponse($hostel, 'Hostel created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            Hostel::with('rooms.beds', 'allocations.student', 'allocations.room')->withCount('rooms', 'allocations')->findOrFail($id),
            'Hostel retrieved'
        );
    }

    public function update(StoreHostelRequest $request, int $id): JsonResponse
    {
        $hostel = Hostel::findOrFail($id);
        $hostel->update($request->validated());
        return $this->updatedResponse($hostel->fresh(), 'Hostel updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Hostel::findOrFail($id)->delete();
        return $this->deletedResponse('Hostel deleted');
    }

    public function getOccupancyReport(int $id): JsonResponse
    {
        $report = $this->hostelService->getOccupancyReport($id);
        return $this->successResponse($report, 'Occupancy report');
    }
}
