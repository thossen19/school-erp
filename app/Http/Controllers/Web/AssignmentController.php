<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Academic\Assignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index()
    {
        $assignments = Assignment::with('class', 'subject', 'teacher')->orderBy('created_at', 'desc')->paginate(15);
        return view('academics.assignments', compact('assignments'));
    }

    public function create()
    {
        return view('academics.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|integer|exists:classes,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'due_date' => 'required|date|after:today',
            'max_marks' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ]);
        $validated['created_by'] = $request->user()->id;
        Assignment::create($validated);
        return redirect()->route('assignments.index')->with('success', 'Assignment created');
    }

    public function show(int $id)
    {
        $assignment = Assignment::with('class', 'subject', 'teacher', 'submissions')->findOrFail($id);
        return view('academics.show', compact('assignment'));
    }

    public function edit(int $id)
    {
        $assignment = Assignment::findOrFail($id);
        return view('academics.edit', compact('assignment'));
    }

    public function update(Request $request, int $id)
    {
        $assignment = Assignment::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'due_date' => 'sometimes|date|after:today',
            'max_marks' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ]);
        $assignment->update($validated);
        return redirect()->route('assignments.index')->with('success', 'Assignment updated');
    }

    public function destroy(int $id)
    {
        Assignment::findOrFail($id)->delete();
        return redirect()->route('assignments.index')->with('success', 'Assignment deleted');
    }
}
