<?php

namespace App\Services\Timetable;

use App\Contracts\RepositoryInterface;
use App\Models\Timetable\Timetable;
use App\Repositories\Timetable\TimetableRepository;
use App\Repositories\Timetable\TimetablePeriodRepository;
use App\Repositories\Timetable\TimetableAllocationRepository;
use App\Repositories\Timetable\SubstitutionRequestRepository;
use App\Repositories\Timetable\RoomAllocationRepository;
use App\Repositories\Academic\ClassRepository;
use App\Repositories\Academic\SubjectRepository;
use App\Repositories\Hr\EmployeeRepository;
use App\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TimetableService extends BaseService
{
    protected TimetableRepository $timetableRepository;
    protected TimetablePeriodRepository $periodRepository;
    protected TimetableAllocationRepository $allocationRepository;
    protected SubstitutionRequestRepository $substitutionRepository;
    protected RoomAllocationRepository $roomAllocationRepository;
    protected ClassRepository $classRepository;
    protected SubjectRepository $subjectRepository;
    protected EmployeeRepository $employeeRepository;

    public function __construct(
        TimetableRepository $timetableRepository,
        TimetablePeriodRepository $periodRepository,
        TimetableAllocationRepository $allocationRepository,
        SubstitutionRequestRepository $substitutionRepository,
        RoomAllocationRepository $roomAllocationRepository,
        ClassRepository $classRepository,
        SubjectRepository $subjectRepository,
        EmployeeRepository $employeeRepository
    ) {
        parent::__construct();
        $this->timetableRepository = $timetableRepository;
        $this->periodRepository = $periodRepository;
        $this->allocationRepository = $allocationRepository;
        $this->substitutionRepository = $substitutionRepository;
        $this->roomAllocationRepository = $roomAllocationRepository;
        $this->classRepository = $classRepository;
        $this->subjectRepository = $subjectRepository;
        $this->employeeRepository = $employeeRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->timetableRepository;
    }

    public function createTimetable(array $data): Timetable
    {
        return DB::transaction(function () use ($data) {
            $existing = $this->timetableRepository->findByClassSectionDay(
                $data['class_id'],
                $data['section_id'] ?? 0,
                $data['day']
            );

            if ($existing) {
                throw new \App\Exceptions\ServiceException("A timetable already exists for this class, section, and day.");
            }

            $data['is_active'] = $data['is_active'] ?? true;
            $timetable = $this->timetableRepository->create($data);

            if (isset($data['periods']) && is_array($data['periods'])) {
                foreach ($data['periods'] as $index => $period) {
                    $period['timetable_id'] = $timetable->id;
                    $period['period_number'] = $index + 1;
                    $this->periodRepository->create($period);
                }
            }

            $this->logActivity('timetable_created', $timetable);

            return $timetable;
        });
    }

    public function generateTimetable(int $classId, int $sectionId, array $preferences = []): Timetable
    {
        return DB::transaction(function () use ($classId, $sectionId, $preferences) {
            $class = $this->classRepository->getClassWithSubjects($classId);
            $subjects = $class->subjects;
            $teachers = $this->employeeRepository->getActiveEmployees();
            $days = $preferences['days'] ?? ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
            $periodsPerDay = $preferences['periods_per_day'] ?? 6;

            $firstDay = $this->timetableRepository->create([
                'class_id' => $classId,
                'section_id' => $sectionId,
                'day' => $days[0],
                'academic_year_id' => $preferences['academic_year_id'] ?? null,
                'is_active' => true,
            ]);

            $periodNumber = 1;
            foreach ($subjects->take($periodsPerDay) as $subject) {
                $teacher = $teachers->random();

                $this->periodRepository->create([
                    'timetable_id' => $firstDay->id,
                    'subject_id' => $subject->id,
                    'employee_id' => $teacher->id,
                    'period_number' => $periodNumber,
                    'start_time' => sprintf('%02d:%02d', 8 + $periodNumber - 1, 0),
                    'end_time' => sprintf('%02d:%02d', 8 + $periodNumber, 0),
                ]);
                $periodNumber++;
            }

            for ($i = 1; $i < count($days); $i++) {
                $this->timetableRepository->cloneTimetable($firstDay->id, $classId, $sectionId);
            }

            $this->logActivity('timetable_generated', [
                'class_id' => $classId,
                'section_id' => $sectionId,
            ]);

            return $firstDay;
        });
    }

    public function detectConflicts(int $timetableId): Collection
    {
        $timetable = $this->timetableRepository->getById($timetableId);
        $periods = $this->periodRepository->findByTimetable($timetableId);
        $conflicts = collect();

        foreach ($periods as $period) {
            if ($period->employee_id) {
                $hasConflict = $this->allocationRepository->checkConflict(
                    $period->employee_id,
                    $timetable->day,
                    $period->start_time,
                    $period->end_time
                );
                if ($hasConflict) {
                    $conflicts->push([
                        'type' => 'teacher',
                        'period' => $period,
                        'message' => "Teacher ID {$period->employee_id} has a scheduling conflict.",
                    ]);
                }
            }
        }

        return $conflicts;
    }

    public function assignTeacher(int $periodId, int $employeeId): \App\Models\Timetable\TimetablePeriod
    {
        return DB::transaction(function () use ($periodId, $employeeId) {
            $period = $this->periodRepository->getById($periodId);
            $employee = $this->employeeRepository->getById($employeeId);

            $timetable = $this->timetableRepository->getById($period->timetable_id);

            $hasConflict = $this->allocationRepository->checkConflict(
                $employeeId,
                $timetable->day,
                $period->start_time,
                $period->end_time
            );

            if ($hasConflict) {
                throw new \App\Exceptions\ServiceException("Teacher has a scheduling conflict for this time slot.");
            }

            $period = $this->periodRepository->update($periodId, ['employee_id' => $employeeId]);

            $this->logActivity('teacher_assigned_to_period', $period);

            return $period;
        });
    }

    public function assignRoom(int $periodId, string $room): \App\Models\Timetable\TimetablePeriod
    {
        return DB::transaction(function () use ($periodId, $room) {
            $period = $this->periodRepository->getById($periodId);
            $timetable = $this->timetableRepository->getById($period->timetable_id);

            $isAvailable = $this->roomAllocationRepository->checkRoomAvailability(
                $room,
                $timetable->day,
                $period->start_time,
                $period->end_time
            );

            if (!$isAvailable) {
                throw new \App\Exceptions\ServiceException("Room is not available for this time slot.");
            }

            $period = $this->periodRepository->update($periodId, ['room' => $room]);

            $this->roomAllocationRepository->allocateRoom([
                'room' => $room,
                'day' => $timetable->day,
                'start_time' => $period->start_time,
                'end_time' => $period->end_time,
                'timetable_period_id' => $periodId,
            ]);

            $this->logActivity('room_assigned_to_period', $period);

            return $period;
        });
    }

    public function createSubstitution(array $data): \App\Models\Timetable\SubstitutionRequest
    {
        return DB::transaction(function () use ($data) {
            $data['status'] = 'pending';
            $substitution = $this->substitutionRepository->create($data);

            $this->logActivity('substitution_request_created', $substitution);

            return $substitution;
        });
    }
}
