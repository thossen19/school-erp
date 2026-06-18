<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentDiscipline;
use Illuminate\Http\Request;

class StudentDisciplineController extends Controller
{
    public function index()
    {
        $records = StudentDiscipline::with('student')->latest()->paginate(15);
        return view('student-disciplines.index', compact('records'));
    }

    public function create()
    {
        return view('student-disciplines.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'incident_date' => 'required|date',
            'incident_type' => 'required|string|max:100',
        ]);
        StudentDiscipline::create($validated);
        return redirect()->route('student-disciplines.index')->with('success', 'Created successfully');
    }

    public function show(int $id)
    {
        $record = StudentDiscipline::with('student')->findOrFail($id);
        return view('student-disciplines.show', compact('record'));
    }

    public function edit(int $id)
    {
        $record = StudentDiscipline::findOrFail($id);
        return view('student-disciplines.edit', compact('record'));
    }

    public function update(Request $request, int $id)
    {
        $record = StudentDiscipline::findOrFail($id);
        $record->update($request->all());
        return redirect()->route('student-disciplines.index')->with('success', 'Updated successfully');
    }

    public function destroy(int $id)
    {
        StudentDiscipline::findOrFail($id)->delete();
        return redirect()->route('student-disciplines.index')->with('success', 'Deleted successfully');
    }
}
