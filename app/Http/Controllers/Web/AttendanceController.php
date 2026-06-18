<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    // Compatibility: old index -> daily
    public function index(Request $request)
    {
        return $this->daily($request);
    }

    public function create()
    {
        $classes = DB::table('classes')->where('school_id', 1)->orderBy('name')->get();
        return view('attendance.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'date' => 'required|date|before_or_equal:today',
            'records' => 'required|array|min:1',
            'records.*.student_id' => 'required|integer|exists:students,id',
            'records.*.status' => 'required|string|in:present,absent,late,half_day',
        ]);

        $schoolId = 1;
        foreach ($validated['records'] as $record) {
            DB::table('attendances')->updateOrInsert(
                ['student_id' => $record['student_id'], 'date' => $validated['date'], 'school_id' => $schoolId],
                ['status' => $record['status'], 'class_id' => $validated['class_id'], 'section_id' => $validated['section_id'] ?? null, 'updated_at' => now()]
            );
        }

        return redirect()->route('attendance.index')->with('success', 'Attendance marked successfully');
    }

    public function show(int $id)
    {
        $record = DB::table('attendances')
            ->leftJoin('students', 'attendances.student_id', '=', 'students.id')
            ->leftJoin('classes', 'attendances.class_id', '=', 'classes.id')
            ->leftJoin('sections', 'attendances.section_id', '=', 'sections.id')
            ->where('attendances.id', $id)
            ->select('attendances.*', 'students.first_name', 'students.last_name', 'classes.name as class_name', 'sections.name as section_name')
            ->firstOrFail();

        return view('attendance.show', compact('record'));
    }

    public function mark(Request $request)
    {
        $schoolId = 1;
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();

        if (!$request->has('class_id') || !$request->has('date')) {
            return view('attendance.mark', compact('classes'));
        }

        $request->validate([
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'date' => 'required|date|before_or_equal:today',
        ]);

        $students = DB::table('students')
            ->leftJoin('classes', 'students.class_id', '=', 'classes.id')
            ->leftJoin('sections', 'students.section_id', '=', 'sections.id')
            ->where('students.class_id', $request->class_id)
            ->when($request->section_id, fn($q) => $q->where('students.section_id', $request->section_id))
            ->where('students.status', 'active')
            ->select('students.*', 'classes.name as class_name', 'sections.name as section_name')
            ->orderBy('students.first_name')
            ->get();

        $existingAttendances = DB::table('attendances')
            ->whereDate('date', $request->date)
            ->where('class_id', $request->class_id)
            ->when($request->section_id, fn($q) => $q->where('section_id', $request->section_id))
            ->get()
            ->keyBy('student_id');

        return view('attendance.mark', compact('students', 'existingAttendances', 'classes'));
    }

    public function daily(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('attendances')
            ->leftJoin('students', 'attendances.student_id', '=', 'students.id')
            ->leftJoin('classes', 'attendances.class_id', '=', 'classes.id')
            ->leftJoin('sections', 'attendances.section_id', '=', 'sections.id')
            ->where('attendances.school_id', $schoolId)
            ->select('attendances.*', 'students.first_name', 'students.last_name', 'students.admission_no', 'classes.name as class_name', 'sections.name as section_name');

        if ($request->date) $query->whereDate('attendances.date', $request->date);
        if ($request->class_id) $query->where('attendances.class_id', $request->class_id);
        if ($request->section_id) $query->where('attendances.section_id', $request->section_id);

        $records = $query->orderBy('attendances.date', 'desc')->paginate(50);
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();
        $sections = DB::table('sections')->where('school_id', $schoolId)->orderBy('name')->get();

        return view('attendance.daily', compact('records', 'classes', 'sections'));
    }

    public function period(Request $request)
    {
        $schoolId = 1;
        $periods = DB::table('attendances')->where('school_id', $schoolId)->whereNotNull('period_id')->distinct()->pluck('period_id');

        $query = DB::table('attendances')
            ->leftJoin('students', 'attendances.student_id', '=', 'students.id')
            ->leftJoin('classes', 'attendances.class_id', '=', 'classes.id')
            ->leftJoin('sections', 'attendances.section_id', '=', 'sections.id')
            ->where('attendances.school_id', $schoolId)
            ->whereNotNull('attendances.period_id')
            ->select('attendances.*', 'students.first_name', 'students.last_name', 'classes.name as class_name', 'sections.name as section_name');

        if ($request->period_id) $query->where('attendances.period_id', $request->period_id);
        if ($request->date) $query->whereDate('attendances.date', $request->date);
        if ($request->class_id) $query->where('attendances.class_id', $request->class_id);

        $records = $query->orderBy('attendances.date', 'desc')->paginate(50);
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();

        return view('attendance.period', compact('records', 'periods', 'classes'));
    }

    public function subject(Request $request)
    {
        $schoolId = 1;
        $subjects = DB::table('subjects')->where('school_id', $schoolId)->orderBy('name')->get();

        $query = DB::table('attendances')
            ->leftJoin('students', 'attendances.student_id', '=', 'students.id')
            ->leftJoin('classes', 'attendances.class_id', '=', 'classes.id')
            ->leftJoin('sections', 'attendances.section_id', '=', 'sections.id')
            ->where('attendances.school_id', $schoolId)
            ->whereNotNull('attendances.subject_id')
            ->select('attendances.*', 'students.first_name', 'students.last_name', 'classes.name as class_name', 'sections.name as section_name');

        if ($request->subject_id) $query->where('attendances.subject_id', $request->subject_id);
        if ($request->date) $query->whereDate('attendances.date', $request->date);
        if ($request->class_id) $query->where('attendances.class_id', $request->class_id);

        $records = $query->orderBy('attendances.date', 'desc')->paginate(50);
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();

        return view('attendance.subject', compact('records', 'subjects', 'classes'));
    }

    public function rfid(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('attendances')
            ->leftJoin('students', 'attendances.student_id', '=', 'students.id')
            ->leftJoin('classes', 'attendances.class_id', '=', 'classes.id')
            ->where('attendances.school_id', $schoolId)
            ->whereNotNull('attendances.rfid_data')
            ->select('attendances.*', 'students.first_name', 'students.last_name', 'classes.name as class_name');

        if ($request->date) $query->whereDate('attendances.date', $request->date);
        if ($request->class_id) $query->where('attendances.class_id', $request->class_id);

        $records = $query->orderBy('attendances.date', 'desc')->paginate(50);
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();
        $rfidEnabled = DB::table('attendance_settings')->where('school_id', $schoolId)->value('rfid_enabled');

        return view('attendance.rfid', compact('records', 'classes', 'rfidEnabled'));
    }

    public function uhf(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('attendances')
            ->leftJoin('students', 'attendances.student_id', '=', 'students.id')
            ->leftJoin('classes', 'attendances.class_id', '=', 'classes.id')
            ->where('attendances.school_id', $schoolId)
            ->whereNotNull('attendances.uhf_data')
            ->select('attendances.*', 'students.first_name', 'students.last_name', 'classes.name as class_name');

        if ($request->date) $query->whereDate('attendances.date', $request->date);
        if ($request->class_id) $query->where('attendances.class_id', $request->class_id);

        $records = $query->orderBy('attendances.date', 'desc')->paginate(50);
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();
        $uhfEnabled = DB::table('attendance_settings')->where('school_id', $schoolId)->value('uhf_enabled');

        return view('attendance.uhf', compact('records', 'classes', 'uhfEnabled'));
    }

    public function biometric(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('attendances')
            ->leftJoin('students', 'attendances.student_id', '=', 'students.id')
            ->leftJoin('classes', 'attendances.class_id', '=', 'classes.id')
            ->where('attendances.school_id', $schoolId)
            ->whereNotNull('attendances.biometric_data')
            ->select('attendances.*', 'students.first_name', 'students.last_name', 'classes.name as class_name');

        if ($request->date) $query->whereDate('attendances.date', $request->date);
        if ($request->class_id) $query->where('attendances.class_id', $request->class_id);

        $records = $query->orderBy('attendances.date', 'desc')->paginate(50);
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();
        $biometricEnabled = DB::table('attendance_settings')->where('school_id', $schoolId)->value('biometric_enabled');

        return view('attendance.biometric', compact('records', 'classes', 'biometricEnabled'));
    }

    public function faceRecognition(Request $request)
    {
        $schoolId = 1;
        $settings = DB::table('attendance_settings')->where('school_id', $schoolId)->first();
        $faceEnabled = $settings ? $settings->face_recognition_enabled : false;

        $query = DB::table('attendances')
            ->leftJoin('students', 'attendances.student_id', '=', 'students.id')
            ->leftJoin('classes', 'attendances.class_id', '=', 'classes.id')
            ->where('attendances.school_id', $schoolId)
            ->whereNotNull('attendances.biometric_data')
            ->select('attendances.*', 'students.first_name', 'students.last_name', 'classes.name as class_name');

        if ($request->date) $query->whereDate('attendances.date', $request->date);

        $records = $query->orderBy('attendances.date', 'desc')->paginate(30);
        return view('attendance.face-recognition', compact('settings', 'faceEnabled', 'records'));
    }

    public function correction(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('attendance_corrections')
            ->leftJoin('attendances', 'attendance_corrections.attendance_id', '=', 'attendances.id')
            ->leftJoin('students', 'attendances.student_id', '=', 'students.id')
            ->leftJoin('classes', 'attendances.class_id', '=', 'classes.id')
            ->where('attendances.school_id', $schoolId)
            ->select('attendance_corrections.*', 'students.first_name', 'students.last_name', 'classes.name as class_name', 'attendances.date as attendance_date');

        if ($request->status) $query->where('attendance_corrections.status', $request->status);

        $records = $query->orderBy('attendance_corrections.created_at', 'desc')->paginate(50);
        return view('attendance.correction', compact('records'));
    }

    public function lateEntry(Request $request)
    {
        $schoolId = 1;
        $threshold = DB::table('attendance_settings')->where('school_id', $schoolId)->value('late_threshold_minutes') ?? 15;

        $query = DB::table('attendances')
            ->leftJoin('students', 'attendances.student_id', '=', 'students.id')
            ->leftJoin('classes', 'attendances.class_id', '=', 'classes.id')
            ->leftJoin('sections', 'attendances.section_id', '=', 'sections.id')
            ->where('attendances.school_id', $schoolId)
            ->where('attendances.status', 'late')
            ->select('attendances.*', 'students.first_name', 'students.last_name', 'students.admission_no', 'classes.name as class_name', 'sections.name as section_name');

        if ($request->date) $query->whereDate('attendances.date', $request->date);
        if ($request->class_id) $query->where('attendances.class_id', $request->class_id);

        $records = $query->orderBy('attendances.date', 'desc')->paginate(50);
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();

        return view('attendance.late-entry', compact('records', 'classes', 'threshold'));
    }

    public function leaveTracking(Request $request)
    {
        $schoolId = 1;

        $leaveRequests = DB::table('leave_requests')
            ->leftJoin('leave_types', 'leave_requests.leave_type_id', '=', 'leave_types.id')
            ->where('leave_requests.school_id', $schoolId)
            ->select('leave_requests.*', 'leave_types.name as leave_type_name', 'leave_types.code as leave_type_code')
            ->orderBy('leave_requests.created_at', 'desc')
            ->paginate(50);

        $leaveBalances = DB::table('leave_balances')
            ->leftJoin('leave_types', 'leave_balances.leave_type_id', '=', 'leave_types.id')
            ->where('leave_balances.school_id', $schoolId)
            ->select('leave_balances.*', 'leave_types.name as leave_type_name')
            ->get();

        $summary = DB::table('leave_requests')
            ->where('school_id', $schoolId)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        return view('attendance.leave-tracking', compact('leaveRequests', 'leaveBalances', 'summary'));
    }

    public function parentNotification(Request $request)
    {
        $schoolId = 1;

        $lateRecords = DB::table('attendances')
            ->leftJoin('students', 'attendances.student_id', '=', 'students.id')
            ->leftJoin('classes', 'attendances.class_id', '=', 'classes.id')
            ->where('attendances.school_id', $schoolId)
            ->whereIn('attendances.status', ['late', 'absent'])
            ->whereDate('attendances.date', today())
            ->select('attendances.*', 'students.first_name', 'students.last_name', 'students.admission_no', 'classes.name as class_name')
            ->orderBy('attendances.updated_at', 'desc')
            ->get();

        $absentCount = $lateRecords->where('status', 'absent')->count();
        $lateCount = $lateRecords->where('status', 'late')->count();

        return view('attendance.parent-notification', compact('lateRecords', 'absentCount', 'lateCount'));
    }

    public function analytics()
    {
        $schoolId = 1;

        $dailyCounts = DB::table('attendances')
            ->where('school_id', $schoolId)
            ->select('date', 'status', DB::raw('count(*) as total'))
            ->groupBy('date', 'status')
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();

        $statusSummary = DB::table('attendances')
            ->where('school_id', $schoolId)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        $classSummary = DB::table('attendances')
            ->leftJoin('classes', 'attendances.class_id', '=', 'classes.id')
            ->where('attendances.school_id', $schoolId)
            ->select('classes.name as class_name', 'attendances.status', DB::raw('count(*) as total'))
            ->groupBy('classes.name', 'attendances.status')
            ->orderBy('classes.name')
            ->get();

        $totalRecords = DB::table('attendances')->where('school_id', $schoolId)->count();
        $todayCount = DB::table('attendances')->where('school_id', $schoolId)->whereDate('date', today())->count();
        $lateThreshold = DB::table('attendance_settings')->where('school_id', $schoolId)->value('late_threshold_minutes') ?? 15;

        return view('attendance.analytics', compact('dailyCounts', 'statusSummary', 'classSummary', 'totalRecords', 'todayCount', 'lateThreshold'));
    }

    public function reports(Request $request)
    {
        $schoolId = 1;

        $query = DB::table('attendances')
            ->leftJoin('students', 'attendances.student_id', '=', 'students.id')
            ->leftJoin('classes', 'attendances.class_id', '=', 'classes.id')
            ->leftJoin('sections', 'attendances.section_id', '=', 'sections.id')
            ->where('attendances.school_id', $schoolId)
            ->select('attendances.*', 'students.first_name', 'students.last_name', 'students.admission_no', 'classes.name as class_name', 'sections.name as section_name');

        if ($request->date_from) $query->whereDate('attendances.date', '>=', $request->date_from);
        if ($request->date_to) $query->whereDate('attendances.date', '<=', $request->date_to);
        if ($request->class_id) $query->where('attendances.class_id', $request->class_id);
        if ($request->status) $query->where('attendances.status', $request->status);

        $records = $query->orderBy('attendances.date', 'desc')->paginate(100);

        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();

        $summary = DB::table('attendances')
            ->where('school_id', $schoolId)
            ->when($request->date_from, fn($q) => $q->whereDate('date', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->whereDate('date', '<=', $request->date_to))
            ->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        return view('attendance.reports', compact('records', 'classes', 'summary'));
    }
}
