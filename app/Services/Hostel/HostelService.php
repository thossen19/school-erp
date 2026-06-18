<?php

namespace App\Services\Hostel;

use App\Contracts\RepositoryInterface;
use App\Exceptions\ServiceException;
use App\Models\Hostel\Hostel;
use App\Repositories\Hostel\HostelRepository;
use App\Repositories\Hostel\HostelRoomRepository;
use App\Repositories\Hostel\HostelAllocationRepository;
use App\Repositories\Hostel\HostelVisitorRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class HostelService extends BaseService
{
    protected HostelRepository $hostelRepository;
    protected HostelRoomRepository $roomRepository;
    protected HostelAllocationRepository $allocationRepository;
    protected HostelVisitorRepository $visitorRepository;

    public function __construct(
        HostelRepository $hostelRepository,
        HostelRoomRepository $roomRepository,
        HostelAllocationRepository $allocationRepository,
        HostelVisitorRepository $visitorRepository
    ) {
        parent::__construct();
        $this->hostelRepository = $hostelRepository;
        $this->roomRepository = $roomRepository;
        $this->allocationRepository = $allocationRepository;
        $this->visitorRepository = $visitorRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->hostelRepository;
    }

    public function createHostel(array $data): Hostel
    {
        return DB::transaction(function () use ($data) {
            $data['is_active'] = $data['is_active'] ?? true;
            $hostel = $this->hostelRepository->create($data);

            if (isset($data['rooms']) && is_array($data['rooms'])) {
                foreach ($data['rooms'] as $room) {
                    $room['hostel_id'] = $hostel->id;
                    $this->roomRepository->create($room);
                }
            }

            $this->logActivity('hostel_created', $hostel);

            return $hostel;
        });
    }

    public function allocateRoom(int $studentId, int $hostelId, int $roomId, array $data = []): \App\Models\Hostel\HostelAllocation
    {
        return DB::transaction(function () use ($studentId, $hostelId, $roomId, $data) {
            $existing = $this->allocationRepository->findByStudent($studentId);
            if ($existing) {
                throw new ServiceException("Student already has an active hostel allocation.");
            }

            $room = $this->roomRepository->getById($roomId);
            if ($room->status !== 'available') {
                throw new ServiceException("Room is not available.");
            }

            $allocation = $this->allocationRepository->allocate($studentId, $hostelId, $roomId, $data);

            $this->roomRepository->updateStatus($roomId, 'occupied');

            $this->logActivity('hostel_room_allocated', $allocation);

            return $allocation;
        });
    }

    public function manageBed(int $hostelId, array $bedData): \App\Models\Hostel\HostelRoom
    {
        return DB::transaction(function () use ($hostelId, $bedData) {
            $bedData['hostel_id'] = $hostelId;
            $room = $this->roomRepository->create($bedData);

            $this->logActivity('hostel_bed_managed', $room);

            return $room;
        });
    }

    public function checkIn(array $data): \App\Models\Hostel\HostelAllocation
    {
        return DB::transaction(function () use ($data) {
            $data['status'] = 'active';
            $data['allocation_date'] = $data['allocation_date'] ?? now();

            $allocation = $this->allocationRepository->create($data);

            if (isset($data['hostel_room_id'])) {
                $this->roomRepository->updateStatus($data['hostel_room_id'], 'occupied');
            }

            $this->logActivity('hostel_check_in', $allocation);

            return $allocation;
        });
    }

    public function checkOut(int $allocationId): \App\Models\Hostel\HostelAllocation
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

            $this->logActivity('hostel_check_out', $allocation);

            return $allocation;
        });
    }

    public function manageVisitor(array $data): \App\Models\Hostel\HostelVisitor
    {
        return DB::transaction(function () use ($data) {
            $data['visit_date'] = $data['visit_date'] ?? now();
            $visitor = $this->visitorRepository->checkIn($data);

            $this->logActivity('hostel_visitor_managed', $visitor);

            return $visitor;
        });
    }

    public function manageLeave(int $allocationId, array $leaveData): \App\Models\Hostel\HostelAllocation
    {
        return DB::transaction(function () use ($allocationId, $leaveData) {
            $allocation = $this->allocationRepository->getById($allocationId);

            $allocation = $this->allocationRepository->update($allocationId, [
                'leave_from' => $leaveData['from_date'],
                'leave_until' => $leaveData['to_date'],
                'leave_reason' => $leaveData['reason'] ?? null,
                'status' => 'on_leave',
            ]);

            $this->logActivity('hostel_leave_managed', $allocation);

            return $allocation;
        });
    }

    public function getHostelReport(int $hostelId): array
    {
        $hostel = $this->hostelRepository->getHostelWithAllocations($hostelId);
        $rooms = $this->roomRepository->findByHostel($hostelId);
        $occupancy = $this->allocationRepository->getCurrentOccupancy($hostelId);

        $report = [
            'hostel' => $hostel->toArray(),
            'total_rooms' => $rooms->count(),
            'available_rooms' => $rooms->where('status', 'available')->count(),
            'occupied_rooms' => $rooms->where('status', 'occupied')->count(),
            'current_occupancy' => $occupancy,
            'capacity' => $hostel->capacity,
            'available_beds' => $hostel->capacity - $occupancy,
        ];

        $this->logActivity('hostel_report_viewed', $report);

        return $report;
    }
}
