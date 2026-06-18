<?php

namespace App\Services\Attendance;

use App\Contracts\RepositoryInterface;
use App\Models\Attendance\Holiday;
use App\Repositories\Attendance\HolidayRepository;
use App\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class HolidayService extends BaseService
{
    protected HolidayRepository $holidayRepository;

    public function __construct(HolidayRepository $holidayRepository)
    {
        parent::__construct();
        $this->holidayRepository = $holidayRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->holidayRepository;
    }

    public function createHoliday(array $data): Holiday
    {
        return DB::transaction(function () use ($data) {
            $existing = $this->holidayRepository->checkIsHoliday($data['date']);
            if ($existing) {
                throw new \App\Exceptions\ServiceException("A holiday already exists on this date.");
            }

            $holiday = $this->holidayRepository->create($data);

            $this->logActivity('holiday_created', $holiday);

            return $holiday;
        });
    }

    public function updateHoliday(int $id, array $data): Holiday
    {
        return DB::transaction(function () use ($id, $data) {
            $holiday = $this->holidayRepository->getById($id);

            if (isset($data['date']) && $data['date'] !== $holiday->date->format('Y-m-d')) {
                $existing = $this->holidayRepository->checkIsHoliday($data['date']);
                if ($existing) {
                    throw new \App\Exceptions\ServiceException("A holiday already exists on this date.");
                }
            }

            $holiday = $this->holidayRepository->update($id, $data);

            $this->logActivity('holiday_updated', $holiday);

            return $holiday;
        });
    }

    public function getHolidayCalendar(int $year): Collection
    {
        return $this->holidayRepository->findByYear($year);
    }
}
