<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Academic\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::withCount('classes', 'teachers')->orderBy('name')->paginate(20);
        return view('subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('subjects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:subjects',
            'type' => 'required|string|in:core,elective,co-curricular',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        Subject::create($validated);
        return redirect()->route('subjects.index')->with('success', 'Subject created successfully');
    }

    public function show(int $id)
    {
        $subject = Subject::with('classes.class', 'teachers.teacher')->withCount('classes', 'teachers')->findOrFail($id);
        return view('subjects.show', compact('subject'));
    }

    public function edit(int $id)
    {
        $subject = Subject::findOrFail($id);
        return view('subjects.edit', compact('subject'));
    }

    public function update(Request $request, int $id)
    {
        $subject = Subject::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'code' => 'sometimes|string|max:20|unique:subjects,code,' . $id,
            'type' => 'sometimes|string|in:core,elective,co-curricular',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);
        $subject->update($validated);
        return redirect()->route('subjects.index')->with('success', 'Subject updated successfully');
    }

    public function destroy(int $id)
    {
        Subject::findOrFail($id)->delete();
        return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully');
    }
}
