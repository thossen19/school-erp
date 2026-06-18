<?php

namespace App\Services\Attendance;

use App\Contracts\RepositoryInterface;
use App\Exceptions\ServiceException;
use App\Models\Attendance\Attendance;
use App\Repositories\Attendance\AttendanceRepository;
use App\Repositories\Student\StudentRepository;
use App\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AttendanceService extends BaseService
{
    protected AttendanceRepository $attendanceRepository;
    protected StudentRepository $studentRepository;

    public function __construct(
        AttendanceRepository $attendanceRepository,
        StudentRepository $studentRepository
    ) {
        $this->attendanceRepository = $attendanceRepository;
        $this->studentRepository = $studentRepository;
        parent::__construct();
    }

    public function repository(): RepositoryInterface
    {
        return $this->attendanceRepository;
    }

    public function markAttendance(int $studentId, array $data): Attendance
    {
        return DB::transaction(function () use ($studentId, $data) {
            $student = $this->studentRepository->getById($studentId);

            $data['student_id'] = $studentId;
            $data['class_id'] = $data['class_id'] ?? $student->class_id;
            $data['section_id'] = $data['section_id'] ?? $student->section_id;
            $data['marked_by'] = $data['marked_by'] ?? auth()->id();

            $attendance = $this->attendanceRepository->upsertAttendance($data);

            $this->logActivity('attendance_marked', $attendance);

            return $attendance;
        });
    }

    public function bulkMarkAttendance(array $records): bool
    {
        return DB::transaction(function () use ($records) {
            $result = $this->attendanceRepository->bulkMarkAttendance($records);

            $this->logActivity('attendance_bulk_marked', ['count' => count($records)]);

            return $result;
        });
    }

    public function getAttendanceByDate(string $date): Collection
    {
        return $this->attendanceRepository->getByDate($date);
    }

    public function getAttendanceByStudent(int $studentId): Collection
    {
        return $this->attendanceRepository->getByStudent($studentId);
    }

    public function getAttendanceByClass(int $classId, ?int $sectionId = null): Collection
    {
        if ($sectionId) {
            return $this->attendanceRepository->query()->where('class_id', $classId)->where('section_id', $sectionId)->get();
        }
        return $this->attendanceRepository->getByClass($classId);
    }

    public function getAttendanceReport(int $classId, ?int $sectionId, string $startDate, string $endDate): array
    {
        $records = $this->attendanceRepository->getAttendanceReport($classId, $sectionId, $startDate, $endDate);

        $summary = [
            'class_id' => $classId,
            'section_id' => $sectionId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_records' => $records->count(),
            'present' => $records->where('status', 'present')->count(),
            'absent' => $records->where('status', 'absent')->count(),
            'late' => $records->where('status', 'late')->count(),
            'half_day' => $records->where('status', 'half_day')->count(),
            'attendance_percentage' => $records->count() > 0
                ? round(($records->where('status', 'present')->count() / $records->count()) * 100, 2)
                : 0,
        ];

        $this->logActivity('attendance_report_viewed', $summary);

        return $summary;
    }

    public function requestCorrection(int $attendanceId, array $data): Attendance
    {
        return DB::transaction(function () use ($attendanceId, $data) {
            $attendance = $this->attendanceRepository->getById($attendanceId);

            $attendance = $this->attendanceRepository->update($attendanceId, [
                'correction_requested' => true,
                'correction_reason' => $data['reason'],
                'requested_new_status' => $data['new_status'],
                'correction_requested_by' => auth()->id(),
                'correction_requested_at' => now(),
            ]);

            $this->logActivity('attendance_correction_requested', $attendance);

            return $attendance;
        });
    }

    public function approveCorrection(int $attendanceId, array $data): Attendance
    {
        return DB::transaction(function () use ($attendanceId, $data) {
            $attendance = $this->attendanceRepository->getById($attendanceId);

            if (!$attendance->correction_requested) {
                throw new ServiceException("No correction request found for this attendance record.");
            }

            $attendance = $this->attendanceRepository->update($attendanceId, [
                'status' => $attendance->requested_new_status ?? $attendance->status,
                'correction_approved' => true,
                'correction_approved_by' => auth()->id(),
                'correction_approved_at' => now(),
                'correction_requested' => false,
                'correction_remarks' => $data['remarks'] ?? null,
            ]);

            $this->logActivity('attendance_correction_approved', $attendance);

            return $attendance;
        });
    }

    public function sendAttendanceAlert(int $studentId, string $type = 'absent'): bool
    {
        try {
            $student = $this->studentRepository->getById($studentId);

            $alertData = [
                'student_id' => $studentId,
                'alert_type' => $type,
                'sent_at' => now(),
            ];

            activity()->causedBy(auth()->user())->performedOn($student)->withProperties($alertData)->event('attendance_alert')->log("AttendanceAlert: {$type} alert sent for student {$studentId}");

            return true;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('AttendanceService@sendAttendanceAlert: ' . $e->getMessage());
            throw new ServiceException("Failed to send attendance alert: " . $e->getMessage());
        }
    }
}
