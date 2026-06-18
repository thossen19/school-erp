<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('events')->where('school_id', 1);

        if ($request->filled('type')) {
            $query->where('event_type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $events = $query->orderBy('start_date', 'desc')->paginate(15);

        $registrationCounts = DB::table('event_registrations')
            ->select('event_id', DB::raw('count(*) as total'))
            ->groupBy('event_id')
            ->pluck('total', 'event_id');

        return view('events.index', compact('events', 'registrationCounts'));
    }

    public function competitions(Request $request)
    {
        $query = DB::table('events')->where('school_id', 1)->where('event_type', 'competition');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $events = $query->orderBy('start_date', 'desc')->paginate(15);
        return view('events.competitions', compact('events'));
    }

    public function workshops(Request $request)
    {
        $query = DB::table('events')->where('school_id', 1)->where('event_type', 'workshop');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $events = $query->orderBy('start_date', 'desc')->paginate(15);
        return view('events.workshops', compact('events'));
    }

    public function sports(Request $request)
    {
        $query = DB::table('events')->where('school_id', 1)->where('event_type', 'sports');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $events = $query->orderBy('start_date', 'desc')->paginate(15);
        return view('events.sports', compact('events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'event_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'venue' => 'nullable|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'max_participants' => 'nullable|integer|min:0',
            'registration_required' => 'boolean',
            'fee' => 'nullable|numeric|min:0',
            'status' => 'required|string',
            'color' => 'nullable|string|max:7',
        ]);

        $validated['school_id'] = 1;
        $validated['registration_required'] = $request->boolean('registration_required');
        $validated['is_paid_event'] = ($validated['fee'] ?? 0) > 0;

        DB::table('events')->insert($validated);

        return redirect()->back()->with('success', 'Event created successfully');
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'event_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'venue' => 'nullable|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'max_participants' => 'nullable|integer|min:0',
            'registration_required' => 'boolean',
            'fee' => 'nullable|numeric|min:0',
            'status' => 'required|string',
            'color' => 'nullable|string|max:7',
        ]);

        $validated['registration_required'] = $request->boolean('registration_required');
        $validated['is_paid_event'] = ($validated['fee'] ?? 0) > 0;

        DB::table('events')->where('id', $id)->where('school_id', 1)->update($validated);

        return redirect()->back()->with('success', 'Event updated successfully');
    }

    public function destroy(int $id)
    {
        DB::table('events')->where('id', $id)->where('school_id', 1)->delete();
        return redirect()->back()->with('success', 'Event deleted successfully');
    }

    public function clubs(Request $request)
    {
        $query = DB::table('clubs')->where('school_id', 1);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $clubs = $query->orderBy('name')->paginate(15);

        $memberCounts = DB::table('club_members')
            ->select('club_id', DB::raw('count(*) as total'))
            ->where('is_active', true)
            ->groupBy('club_id')
            ->pluck('total', 'club_id');

        return view('events.clubs', compact('clubs', 'memberCounts'));
    }

    public function storeClub(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'club_type' => 'nullable|string|max:50',
            'code' => 'nullable|string|max:50',
            'max_members' => 'nullable|integer|min:0',
            'meeting_schedule' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $validated['school_id'] = 1;
        $validated['status'] = $request->boolean('status');

        DB::table('clubs')->insert($validated);

        return redirect()->back()->with('success', 'Club created successfully');
    }

    public function updateClub(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'club_type' => 'nullable|string|max:50',
            'code' => 'nullable|string|max:50',
            'max_members' => 'nullable|integer|min:0',
            'meeting_schedule' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $validated['status'] = $request->boolean('status');

        DB::table('clubs')->where('id', $id)->where('school_id', 1)->update($validated);

        return redirect()->back()->with('success', 'Club updated successfully');
    }

    public function destroyClub(int $id)
    {
        DB::table('clubs')->where('id', $id)->where('school_id', 1)->delete();
        return redirect()->back()->with('success', 'Club deleted successfully');
    }

    public function storeClubMember(Request $request)
    {
        $validated = $request->validate([
            'club_id' => 'required|integer|exists:clubs,id',
            'student_id' => 'required|integer|exists:students,id',
            'role' => 'nullable|string|max:50',
            'joined_date' => 'nullable|date',
        ]);

        $validated['school_id'] = 1;
        $validated['is_active'] = true;
        $validated['joined_date'] = $validated['joined_date'] ?? now()->toDateString();

        DB::table('club_members')->insert($validated);

        return redirect()->back()->with('success', 'Member added to club');
    }

    public function removeClubMember(int $id)
    {
        DB::table('club_members')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Member removed from club');
    }

    public function calendar(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $events = DB::table('events')
            ->where('school_id', 1)
            ->whereYear('start_date', $year)
            ->whereMonth('start_date', $month)
            ->orderBy('start_date')
            ->get();

        return view('events.calendar', compact('events', 'month', 'year'));
    }

    public function registration(Request $request)
    {
        $eventId = $request->input('event_id');

        $query = DB::table('event_registrations')
            ->join('events', 'event_registrations.event_id', '=', 'events.id')
            ->leftJoin('students', 'event_registrations.student_id', '=', 'students.id')
            ->select(
                'event_registrations.*',
                'events.title as event_title',
                'events.start_date',
                'students.first_name',
                'students.last_name',
                'students.admission_no'
            )
            ->where('events.school_id', 1);

        if ($eventId) {
            $query->where('event_registrations.event_id', $eventId);
        }

        $registrations = $query->orderBy('event_registrations.created_at', 'desc')->paginate(15);
        $events = DB::table('events')->where('school_id', 1)->orderBy('title')->get();

        return view('events.registration', compact('registrations', 'events', 'eventId'));
    }

    public function storeRegistration(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|integer|exists:events,id',
            'student_id' => 'required|integer|exists:students,id',
        ]);

        $exists = DB::table('event_registrations')
            ->where('event_id', $validated['event_id'])
            ->where('student_id', $validated['student_id'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Student already registered for this event');
        }

        DB::table('event_registrations')->insert([
            'event_id' => $validated['event_id'],
            'student_id' => $validated['student_id'],
            'registration_date' => now()->toDateString(),
            'status' => 'registered',
        ]);

        return redirect()->back()->with('success', 'Registration created successfully');
    }

    public function destroyRegistration(int $id)
    {
        DB::table('event_registrations')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Registration cancelled');
    }

    public function attendance(Request $request)
    {
        $eventId = $request->input('event_id');

        $query = DB::table('event_registrations')
            ->join('events', 'event_registrations.event_id', '=', 'events.id')
            ->leftJoin('students', 'event_registrations.student_id', '=', 'students.id')
            ->select(
                'event_registrations.*',
                'events.title as event_title',
                'events.start_date',
                'students.first_name',
                'students.last_name',
                'students.admission_no'
            )
            ->where('events.school_id', 1);

        if ($eventId) {
            $query->where('event_registrations.event_id', $eventId);
        }

        $registrations = $query->orderBy('event_registrations.created_at', 'desc')->paginate(15);
        $events = DB::table('events')->where('school_id', 1)->orderBy('title')->get();

        return view('events.attendance', compact('registrations', 'events', 'eventId'));
    }

    public function markAttendance(Request $request, int $id)
    {
        $validated = $request->validate([
            'attended' => 'required|in:yes,no',
        ]);

        DB::table('event_registrations')->where('id', $id)->update([
            'attended' => $validated['attended'],
        ]);

        return redirect()->back()->with('success', 'Attendance marked successfully');
    }

    public function reports()
    {
        $totalEvents = DB::table('events')->where('school_id', 1)->count();
        $upcomingEvents = DB::table('events')->where('school_id', 1)->where('start_date', '>=', now()->toDateString())->whereIn('status', ['published', 'upcoming'])->count();
        $totalRegistrations = DB::table('event_registrations')->join('events', 'event_registrations.event_id', '=', 'events.id')->where('events.school_id', 1)->count();
        $totalAttended = DB::table('event_registrations')->join('events', 'event_registrations.event_id', '=', 'events.id')->where('events.school_id', 1)->where('attended', 'yes')->count();
        $totalClubs = DB::table('clubs')->where('school_id', 1)->where('status', true)->count();
        $totalClubMembers = DB::table('club_members')->where('school_id', 1)->where('is_active', true)->count();

        $eventsByType = DB::table('events')->where('school_id', 1)->select('event_type', DB::raw('count(*) as total'))->groupBy('event_type')->get();
        $eventsByStatus = DB::table('events')->where('school_id', 1)->select('status', DB::raw('count(*) as total'))->groupBy('status')->get();
        $monthlyEvents = DB::table('events')->where('school_id', 1)->whereYear('start_date', now()->year)->select(DB::raw('MONTH(start_date) as month'), DB::raw('count(*) as total'))->groupBy(DB::raw('MONTH(start_date)'))->orderBy('month')->get();

        return view('events.reports', compact(
            'totalEvents', 'upcomingEvents', 'totalRegistrations', 'totalAttended',
            'totalClubs', 'totalClubMembers', 'eventsByType', 'eventsByStatus', 'monthlyEvents'
        ));
    }
}
