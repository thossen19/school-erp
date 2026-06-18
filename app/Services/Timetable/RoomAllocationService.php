<?php

namespace App\Services\Timetable;

use App\Contracts\RepositoryInterface;
use App\Models\Timetable\RoomAllocation;
use App\Repositories\Timetable\RoomAllocationRepository;
use App\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RoomAllocationService extends BaseService
{
    protected RoomAllocationRepository $roomAllocationRepository;

    public function __construct(RoomAllocationRepository $roomAllocationRepository)
    {
        parent::__construct();
        $this->roomAllocationRepository = $roomAllocationRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->roomAllocationRepository;
    }

    public function allocateRoom(array $data): RoomAllocation
    {
        return DB::transaction(function () use ($data) {
            $isAvailable = $this->roomAllocationRepository->checkRoomAvailability(
                $data['room'],
                $data['day'],
                $data['start_time'],
                $data['end_time']
            );

            if (!$isAvailable) {
                throw new \App\Exceptions\ServiceException("Room is not available during this time slot.");
            }

            $allocation = $this->roomAllocationRepository->allocateRoom($data);

            $this->logActivity('room_allocated', $allocation);

            return $allocation;
        });
    }

    public function checkAvailability(string $room, string $day, string $startTime, string $endTime): bool
    {
        return !$this->roomAllocationRepository->checkRoomAvailability($room, $day, $startTime, $endTime);
    }

    public function getRoomSchedule(string $room, string $day): Collection
    {
        return $this->roomAllocationRepository->getRoomSchedule($room, $day);
    }
}
