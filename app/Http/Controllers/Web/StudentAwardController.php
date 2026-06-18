<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentAward;
use Illuminate\Http\Request;

class StudentAwardController extends Controller
{
    public function index()
    {
        $awards = StudentAward::with('student')->latest()->paginate(15);
        return view('student-awards.index', compact('awards'));
    }

    public function create()
    {
        return view('student-awards.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'award_name' => 'required|string|max:255',
            'date_awarded' => 'required|date',
        ]);
        StudentAward::create($validated);
        return redirect()->route('student-awards.index')->with('success', 'Created successfully');
    }

    public function show(int $id)
    {
        $award = StudentAward::with('student')->findOrFail($id);
        return view('student-awards.show', compact('award'));
    }

    public function edit(int $id)
    {
        $award = StudentAward::findOrFail($id);
        return view('student-awards.edit', compact('award'));
    }

    public function update(Request $request, int $id)
    {
        $award = StudentAward::findOrFail($id);
        $award->update($request->all());
        return redirect()->route('student-awards.index')->with('success', 'Updated successfully');
    }

    public function destroy(int $id)
    {
        StudentAward::findOrFail($id)->delete();
        return redirect()->route('student-awards.index')->with('success', 'Deleted successfully');
    }
}
