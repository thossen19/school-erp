<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimetableController extends Controller
{
    public function index(Request $request)
    {
        return $this->classScheduling($request);
    }

    public function create()
    {
        $schoolId = 1;
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();
        $sections = DB::table('sections')->where('school_id', $schoolId)->orderBy('name')->get();
        $academicYears = DB::table('academic_years')->where('school_id', $schoolId)->orderBy('start_date', 'desc')->get();
        return view('timetables.create', compact('classes', 'sections', 'academicYears'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'academic_year_id' => 'required|integer|exists:academic_years,id',
            'effective_from' => 'nullable|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'is_active' => 'boolean',
        ]);

        DB::table('timetables')->insert([
            'school_id' => 1,
            'class_id' => (string) $validated['class_id'],
            'section_id' => isset($validated['section_id']) ? (string) $validated['section_id'] : null,
            'name' => $validated['name'],
            'academic_year_id' => $validated['academic_year_id'],
            'effective_from' => $validated['effective_from'] ?? null,
            'effective_to' => $validated['effective_to'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('timetables.index')->with('success', 'Timetable created successfully');
    }

    public function show(int $id)
    {
        $timetable = DB::table('timetables')
            ->leftJoin('classes', 'timetables.class_id', '=', 'classes.id')
            ->leftJoin('sections', 'timetables.section_id', '=', 'sections.id')
            ->where('timetables.id', $id)
            ->select('timetables.*', 'classes.name as class_name', 'sections.name as section_name')
            ->first();
        if (!$timetable) abort(404);

        $periods = DB::table('timetable_periods')
            ->where('timetable_id', $id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('timetables.show', compact('timetable', 'periods'));
    }

    public function edit(int $id)
    {
        $timetable = DB::table('timetables')->where('id', $id)->first();
        if (!$timetable) abort(404);
        $schoolId = 1;
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();
        $sections = DB::table('sections')->where('school_id', $schoolId)->orderBy('name')->get();
        $academicYears = DB::table('academic_years')->where('school_id', $schoolId)->orderBy('start_date', 'desc')->get();
        return view('timetables.edit', compact('timetable', 'classes', 'sections', 'academicYears'));
    }

    public function update(Request $request, int $id)
    {
        $timetable = DB::table('timetables')->where('id', $id)->first();
        if (!$timetable) abort(404);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'class_id' => 'sometimes|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'academic_year_id' => 'sometimes|integer|exists:academic_years,id',
            'effective_from' => 'nullable|date',
            'effective_to' => 'nullable|date|after_or_equal:effective_from',
            'is_active' => 'boolean',
        ]);

        $data = [];
        foreach (['name', 'academic_year_id', 'effective_from', 'effective_to'] as $f) {
            if (isset($validated[$f])) $data[$f] = $validated[$f];
        }
        if (isset($validated['class_id'])) $data['class_id'] = (string) $validated['class_id'];
        if (isset($validated['section_id'])) $data['section_id'] = (string) $validated['section_id'];
        $data['is_active'] = $request->boolean('is_active');
        $data['updated_at'] = now();

        DB::table('timetables')->where('id', $id)->update($data);
        return redirect()->route('timetables.index')->with('success', 'Timetable updated successfully');
    }

    public function destroy(int $id)
    {
        DB::table('timetables')->where('id', $id)->delete();
        return redirect()->route('timetables.index')->with('success', 'Timetable deleted successfully');
    }

    // --- New submenu methods ---

    public function classScheduling(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('timetables')
            ->leftJoin('classes', 'timetables.class_id', '=', 'classes.id')
            ->leftJoin('sections', 'timetables.section_id', '=', 'sections.id')
            ->leftJoin('academic_years', 'timetables.academic_year_id', '=', 'academic_years.id')
            ->where('timetables.school_id', $schoolId)
            ->select('timetables.*', 'classes.name as class_name', 'sections.name as section_name', 'academic_years.name as academic_year',
                DB::raw('(select count(*) from timetable_periods where timetable_periods.timetable_id = timetables.id) as periods_count'));

        if ($request->class_id) $query->where('timetables.class_id', $request->class_id);
        if ($request->is_active !== null) $query->where('timetables.is_active', $request->is_active);

        $timetables = $query->orderBy('classes.name')->paginate(50);
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();

        return view('timetables.class-scheduling', compact('timetables', 'classes'));
    }

    public function teacherAllocation(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('timetable_periods')
            ->leftJoin('timetables', 'timetable_periods.timetable_id', '=', 'timetables.id')
            ->leftJoin('classes', 'timetables.class_id', '=', 'classes.id')
            ->leftJoin('sections', 'timetables.section_id', '=', 'sections.id')
            ->leftJoin('subjects', 'timetable_periods.subject_id', '=', 'subjects.id')
            ->where('timetables.school_id', $schoolId)
            ->select('timetable_periods.*', 'timetables.name as timetable_name',
                'classes.name as class_name', 'sections.name as section_name', 'subjects.name as subject_name');

        if ($request->teacher_id) $query->where('timetable_periods.teacher_id', $request->teacher_id);
        if ($request->day) $query->where('timetable_periods.day_of_week', $request->day);

        $periods = $query->orderBy('timetable_periods.day_of_week')->orderBy('timetable_periods.start_time')->paginate(50);
        $teachers = DB::table('employees')->where('school_id', $schoolId)->orderBy('first_name')->get();

        return view('timetables.teacher-allocation', compact('periods', 'teachers'));
    }

    public function subjectAllocation(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('timetable_periods')
            ->leftJoin('timetables', 'timetable_periods.timetable_id', '=', 'timetables.id')
            ->leftJoin('classes', 'timetables.class_id', '=', 'classes.id')
            ->leftJoin('subjects', 'timetable_periods.subject_id', '=', 'subjects.id')
            ->where('timetables.school_id', $schoolId)
            ->select('timetable_periods.*', 'timetables.name as timetable_name', 'classes.name as class_name', 'subjects.name as subject_name');

        if ($request->subject_id) $query->where('timetable_periods.subject_id', $request->subject_id);
        if ($request->day) $query->where('timetable_periods.day_of_week', $request->day);

        $periods = $query->orderBy('timetable_periods.day_of_week')->orderBy('timetable_periods.start_time')->paginate(50);
        $subjects = DB::table('subjects')->where('school_id', $schoolId)->orderBy('name')->get();

        return view('timetables.subject-allocation', compact('periods', 'subjects'));
    }

    public function roomAllocation(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('room_allocations')->where('school_id', $schoolId);

        if ($request->type) $query->where('type', $request->type);
        if ($request->status !== null) $query->where('status', $request->status);

        $rooms = $query->orderBy('name')->paginate(50);
        return view('timetables.room-allocation', compact('rooms'));
    }

    public function conflictDetection(Request $request)
    {
        $schoolId = 1;
        $conflicts = collect();
        $checkedDay = $request->day;

        $periods = DB::table('timetable_periods')
            ->leftJoin('timetables', 'timetable_periods.timetable_id', '=', 'timetables.id')
            ->leftJoin('classes', 'timetables.class_id', '=', 'classes.id')
            ->leftJoin('subjects', 'timetable_periods.subject_id', '=', 'subjects.id')
            ->where('timetables.school_id', $schoolId)
            ->where('timetable_periods.is_break', 0)
            ->when($checkedDay, fn($q) => $q->where('timetable_periods.day_of_week', $checkedDay))
            ->select('timetable_periods.*', 'timetables.name as timetable_name', 'classes.name as class_name', 'subjects.name as subject_name')
            ->orderBy('timetable_periods.day_of_week')
            ->orderBy('timetable_periods.start_time')
            ->get();

        // Teacher conflicts: same teacher, same day, overlapping times
        $byTeacher = $periods->groupBy('teacher_id');
        foreach ($byTeacher as $teacherId => $teacherPeriods) {
            if (!$teacherId) continue;
            for ($i = 0; $i < count($teacherPeriods); $i++) {
                for ($j = $i + 1; $j < count($teacherPeriods); $j++) {
                    $a = $teacherPeriods[$i];
                    $b = $teacherPeriods[$j];
                    if ($a->day_of_week === $b->day_of_week && $a->start_time < $b->end_time && $b->start_time < $a->end_time) {
                        $conflicts->push((object)[
                            'type' => 'Teacher',
                            'detail' => "Teacher #{$teacherId} has overlapping periods: {$a->timetable_name} ({$a->class_name}) {$a->start_time}-{$a->end_time} vs {$b->timetable_name} ({$b->class_name}) {$b->start_time}-{$b->end_time}",
                            'day' => $a->day_of_week,
                        ]);
                    }
                }
            }
        }

        // Room conflicts: same room, same day, overlapping times
        $byRoom = $periods->groupBy('room_id');
        foreach ($byRoom as $roomId => $roomPeriods) {
            if (!$roomId) continue;
            for ($i = 0; $i < count($roomPeriods); $i++) {
                for ($j = $i + 1; $j < count($roomPeriods); $j++) {
                    $a = $roomPeriods[$i];
                    $b = $roomPeriods[$j];
                    if ($a->day_of_week === $b->day_of_week && $a->start_time < $b->end_time && $b->start_time < $a->end_time) {
                        $conflicts->push((object)[
                            'type' => 'Room',
                            'detail' => "Room #{$roomId} double-booked: {$a->timetable_name} ({$a->class_name}) {$a->start_time}-{$a->end_time} vs {$b->timetable_name} ({$b->class_name}) {$b->start_time}-{$b->end_time}",
                            'day' => $a->day_of_week,
                        ]);
                    }
                }
            }
        }

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        return view('timetables.conflict-detection', compact('conflicts', 'days', 'checkedDay'));
    }

    public function aiGenerator()
    {
        $schoolId = 1;
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();
        $subjects = DB::table('subjects')->where('school_id', $schoolId)->orderBy('name')->get();
        $teachers = DB::table('employees')->where('school_id', $schoolId)->orderBy('first_name')->get();
        $rooms = DB::table('room_allocations')->where('school_id', $schoolId)->where('status', 1)->get();
        $totalTimetables = DB::table('timetables')->where('school_id', $schoolId)->count();
        $totalPeriods = DB::table('timetable_periods')
            ->leftJoin('timetables', 'timetable_periods.timetable_id', '=', 'timetables.id')
            ->where('timetables.school_id', $schoolId)
            ->count();

        return view('timetables.ai-generator', compact('classes', 'subjects', 'teachers', 'rooms', 'totalTimetables', 'totalPeriods'));
    }

    public function examTimetable(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('timetables')
            ->leftJoin('classes', 'timetables.class_id', '=', 'classes.id')
            ->leftJoin('sections', 'timetables.section_id', '=', 'sections.id')
            ->where('timetables.school_id', $schoolId)
            ->select('timetables.*', 'classes.name as class_name', 'sections.name as section_name');

        if ($request->class_id) $query->where('timetables.class_id', $request->class_id);

        $timetables = $query->orderBy('classes.name')->paginate(50);
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();

        return view('timetables.exam-timetable', compact('timetables', 'classes'));
    }

    public function timetableReports()
    {
        $schoolId = 1;

        $totalTimetables = DB::table('timetables')->where('school_id', $schoolId)->count();
        $activeTimetables = DB::table('timetables')->where('school_id', $schoolId)->where('is_active', 1)->count();
        $totalPeriods = DB::table('timetable_periods')
            ->leftJoin('timetables', 'timetable_periods.timetable_id', '=', 'timetables.id')
            ->where('timetables.school_id', $schoolId)
            ->count();
        $totalRooms = DB::table('room_allocations')->where('school_id', $schoolId)->count();

        $byDay = DB::table('timetable_periods')
            ->select('day_of_week', DB::raw('count(*) as total'))
            ->groupBy('day_of_week')
            ->orderBy('day_of_week')
            ->get();

        $byClass = DB::table('timetables')
            ->leftJoin('classes', 'timetables.class_id', '=', 'classes.id')
            ->where('timetables.school_id', $schoolId)
            ->select('classes.name as class_name', DB::raw('count(*) as total'))
            ->groupBy('classes.name')
            ->orderBy('classes.name')
            ->get();

        $periodCounts = DB::table('timetable_periods')
            ->leftJoin('timetables', 'timetable_periods.timetable_id', '=', 'timetables.id')
            ->leftJoin('classes', 'timetables.class_id', '=', 'classes.id')
            ->where('timetables.school_id', $schoolId)
            ->select('classes.name as class_name', DB::raw('count(*) as total'))
            ->groupBy('classes.name')
            ->orderBy('classes.name')
            ->get();

        return view('timetables.timetable-reports', compact(
            'totalTimetables', 'activeTimetables', 'totalPeriods', 'totalRooms',
            'byDay', 'byClass', 'periodCounts'
        ));
    }
}
