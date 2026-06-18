<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\BulkAttendanceRequest;
use App\Http\Requests\Attendance\StoreAttendanceRequest;
use App\Models\Attendance\Attendance;
use App\Models\Attendance\AttendanceCorrection;
use App\Services\Attendance\AttendanceService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    use ApiResponseTrait;

    protected AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function index(Request $request): JsonResponse
    {
        $records = Attendance::with('student', 'class', 'section')->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))->when($request->section_id, fn($q) => $q->where('section_id', $request->section_id))->when($request->date, fn($q) => $q->whereDate('date', $request->date))->when($request->student_id, fn($q) => $q->where('student_id', $request->student_id))->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->date_from, fn($q) => $q->whereDate('date', '>=', $request->date_from))->when($request->date_to, fn($q) => $q->whereDate('date', '<=', $request->date_to))->orderBy('date', 'desc')->paginate($request->per_page ?? 50);

        return $this->paginatedResponse($records, 'Attendance records retrieved');
    }

    public function store(StoreAttendanceRequest $request): JsonResponse
    {
        $attendance = $this->attendanceService->markAttendance($request->student_id, $request->validated());
        return $this->createdResponse($attendance->load('student'), 'Attendance marked');
    }

    public function bulkMark(BulkAttendanceRequest $request): JsonResponse
    {
        $result = $this->attendanceService->bulkMarkAttendance($request->validated()['records']);
        return $this->successResponse(['marked' => $result], 'Bulk attendance marked');
    }

    public function getByDate(string $date, Request $request): JsonResponse
    {
        $records = Attendance::with('student:id,first_name,last_name,admission_no,class_id,section_id')->whereDate('date', $date)->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))->when($request->section_id, fn($q) => $q->where('section_id', $request->section_id))->get();
        return $this->successResponse($records, 'Attendance by date');
    }

    public function getByStudent(int $studentId, Request $request): JsonResponse
    {
        $records = $this->attendanceService->getAttendanceByStudent($studentId);
        $summary = [
            'total' => $records->count(),
            'present' => $records->where('status', 'present')->count(),
            'absent' => $records->where('status', 'absent')->count(),
            'late' => $records->where('status', 'late')->count(),
            'half_day' => $records->where('status', 'half_day')->count(),
            'attendance_percentage' => $records->count() > 0
                ? round(($records->where('status', 'present')->count() / $records->count()) * 100, 2)
                : 0,
        ];
        return $this->successResponse(['records' => $records, 'summary' => $summary], 'Attendance by student');
    }

    public function getByClass(int $classId, Request $request): JsonResponse
    {
        $records = $this->attendanceService->getAttendanceByClass($classId, $request->section_id);
        return $this->successResponse($records, 'Attendance by class');
    }

    public function getReport(Request $request): JsonResponse
    {
        $request->validate([
            'class_id' => 'required|integer|exists:classes,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $report = $this->attendanceService->getAttendanceReport(
            $request->class_id,
            $request->section_id,
            $request->start_date,
            $request->end_date
        );

        return $this->successResponse($report, 'Attendance report');
    }

    public function requestCorrection(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'attendance_id' => 'required|integer|exists:attendances,id',
            'requested_status' => 'required|string|in:present,absent,late,half_day',
            'reason' => 'required|string|max:500',
        ]);

        $correction = AttendanceCorrection::create(array_merge($validated, [
            'requested_by' => $request->user()->id,
            'status' => 'pending',
        ]));

        return $this->createdResponse($correction, 'Correction requested');
    }

    public function approveCorrection(Request $request, int $correctionId): JsonResponse
    {
        $correction = AttendanceCorrection::findOrFail($correctionId);
        $correction->update(['status' => 'approved', 'approved_by' => $request->user()->id, 'approved_at' => now()]);

        Attendance::where('id', $correction->attendance_id)->update(['status' => $correction->requested_status]);

        return $this->successResponse($correction, 'Correction approved');
    }

    public function getAnalytics(Request $request): JsonResponse
    {
        $analytics = $this->attendanceService->getAnalytics(
            $request->get('school_id'),
            $request->get('academic_year_id'),
            $request->class_id,
            $request->section_id
        );
        return $this->successResponse($analytics, 'Attendance analytics');
    }
}
