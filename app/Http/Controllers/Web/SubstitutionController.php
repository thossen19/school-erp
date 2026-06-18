<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Timetable\SubstitutionRequest;
use App\Models\Timetable\TimetablePeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubstitutionController extends Controller
{
    public function index(Request $request)
    {
        $substitutions = SubstitutionRequest::when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->date, fn($q) => $q->whereDate('date', $request->date))
            ->when($request->teacher_id, fn($q) => $q->where('original_teacher_id', $request->teacher_id))
            ->orderBy('created_at', 'desc')->paginate(20);

        // Attach teacher names
        $substitutions->getCollection()->transform(function ($s) {
            $s->original_teacher_name = DB::table('users')->where('id', $s->original_teacher_id)->value('name') ?? 'Unknown';
            $s->substitute_teacher_name = $s->substitute_teacher_id ? (DB::table('users')->where('id', $s->substitute_teacher_id)->value('name') ?? 'Unknown') : '-';
            return $s;
        });

        return view('timetables.substitutions.index', compact('substitutions'));
    }

    public function create()
    {
        $teachers = DB::table('users')
            ->join('employees', 'users.id', '=', 'employees.user_id')
            ->where('employees.status', 'active')
            ->select('users.id', 'users.name')
            ->orderBy('users.name')
            ->get();

        return view('timetables.substitutions.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'timetable_period_id' => 'required|integer|exists:timetable_periods,id',
            'original_teacher_id' => 'required|integer|exists:users,id',
            'substitute_teacher_id' => 'nullable|integer|exists:users,id',
            'date' => 'required|date',
            'reason' => 'nullable|string|max:1000',
            'status' => 'sometimes|string|in:pending,approved,rejected',
        ]);

        $validated['school_id'] = session('school_id', 1);
        $validated['status'] ??= 'pending';

        DB::table('substitution_requests')->insert($validated);
        return redirect()->route('timetable.substitutions')->with('success', 'Substitution request created');
    }

    public function show(int $id)
    {
        $substitution = SubstitutionRequest::findOrFail($id);
        $substitution->original_teacher_name = DB::table('users')->where('id', $substitution->original_teacher_id)->value('name') ?? 'Unknown';
        $substitution->substitute_teacher_name = $substitution->substitute_teacher_id ? (DB::table('users')->where('id', $substitution->substitute_teacher_id)->value('name') ?? 'Unknown') : '-';
        return view('timetables.substitutions.show', compact('substitution'));
    }

    public function approve(int $id)
    {
        DB::table('substitution_requests')->where('id', $id)->update([
            'status' => 'approved',
            'updated_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Substitution approved');
    }

    public function reject(int $id)
    {
        DB::table('substitution_requests')->where('id', $id)->update([
            'status' => 'rejected',
            'updated_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Substitution rejected');
    }
}
