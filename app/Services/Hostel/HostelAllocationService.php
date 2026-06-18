<?php

namespace App\Services\Hostel;

use App\Contracts\RepositoryInterface;
use App\Exceptions\ServiceException;
use App\Models\Hostel\HostelAllocation;
use App\Repositories\Hostel\HostelAllocationRepository;
use App\Repositories\Hostel\HostelRoomRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class HostelAllocationService extends BaseService
{
    protected HostelAllocationRepository $allocationRepository;
    protected HostelRoomRepository $roomRepository;

    public function __construct(
        HostelAllocationRepository $allocationRepository,
        HostelRoomRepository $roomRepository
    ) {
        parent::__construct();
        $this->allocationRepository = $allocationRepository;
        $this->roomRepository = $roomRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->allocationRepository;
    }

    public function allocate(int $studentId, int $hostelId, int $roomId, array $data = []): HostelAllocation
    {
        return DB::transaction(function () use ($studentId, $hostelId, $roomId, $data) {
            $existing = $this->allocationRepository->findByStudent($studentId);
            if ($existing) {
                throw new ServiceException("Student already has an active allocation.");
            }

            $room = $this->roomRepository->getById($roomId);
            if ($room->status !== 'available') {
                throw new ServiceException("Room is not available.");
            }

            $allocation = $this->allocationRepository->allocate($studentId, $hostelId, $roomId, $data);
            $this->roomRepository->updateStatus($roomId, 'occupied');

            $this->logActivity('hostel_allocated', $allocation);

            return $allocation;
        });
    }

    public function reallocate(int $allocationId, int $newRoomId): HostelAllocation
    {
        return DB::transaction(function () use ($allocationId, $newRoomId) {
            $allocation = $this->allocationRepository->getById($allocationId);

            if ($allocation->status !== 'active') {
                throw new ServiceException("Cannot reallocate an inactive allocation.");
            }

            $oldRoomId = $allocation->hostel_room_id;

            $newRoom = $this->roomRepository->getById($newRoomId);
            if ($newRoom->status !== 'available') {
                throw new ServiceException("New room is not available.");
            }

            $this->allocationRepository->update($allocationId, ['hostel_room_id' => $newRoomId]);

            if ($oldRoomId) {
                $this->roomRepository->updateStatus($oldRoomId, 'available');
            }
            $this->roomRepository->updateStatus($newRoomId, 'occupied');

            $allocation = $allocation->fresh();

            $this->logActivity('hostel_reallocated', $allocation);

            return $allocation;
        });
    }

    public function vacate(int $allocationId): HostelAllocation
    {
        return DB::transaction(function () use ($allocationId) {
            $allocation = $this->allocationRepository->getById($allocationId);

            if ($allocation->status !== 'active') {
                throw new ServiceException("Allocation is not active.");
            }

            $this->allocationRepository->deallocate($allocationId);

            if ($allocation->hostel_room_id) {
                $this->roomRepository->updateStatus($allocation->hostel_room_id, 'available');
            }

            $allocation = $allocation->fresh();

            $this->logActivity('hostel_vacated', $allocation);

            return $allocation;
        });
    }

    public function getOccupancyReport(int $hostelId): array
    {
        $totalCapacity = $this->roomRepository->findByHostel($hostelId)->sum('capacity');
        $currentOccupancy = $this->allocationRepository->getCurrentOccupancy($hostelId);
        $availableBeds = $totalCapacity - $currentOccupancy;

        $report = [
            'hostel_id' => $hostelId,
            'total_capacity' => $totalCapacity,
            'current_occupancy' => $currentOccupancy,
            'available_beds' => $availableBeds,
            'occupancy_percentage' => $totalCapacity > 0
                ? round(($currentOccupancy / $totalCapacity) * 100, 2)
                : 0,
        ];

        $this->logActivity('hostel_occupancy_report_viewed', $report);

        return $report;
    }
}
