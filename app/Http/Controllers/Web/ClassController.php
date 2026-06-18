<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Academic\ClassModel;
use App\Models\Academic\Section;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassModel::withCount('sections', 'students')->orderBy('numeric_value')->orderBy('name')->get();
        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        return view('classes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'numeric_value' => 'nullable|integer|min:-2|max:12',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        ClassModel::create($validated);
        return redirect()->route('classes.index')->with('success', 'Class created successfully');
    }

    public function show(int $id)
    {
        $class = ClassModel::with('sections', 'subjects.subject')->withCount('students')->findOrFail($id);
        return view('classes.show', compact('class'));
    }

    public function edit(int $id)
    {
        $class = ClassModel::findOrFail($id);
        return view('classes.edit', compact('class'));
    }

    public function update(Request $request, int $id)
    {
        $class = ClassModel::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'numeric_value' => 'nullable|integer|min:-2|max:12',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);
        $class->update($validated);
        return redirect()->route('classes.index')->with('success', 'Class updated successfully');
    }

    public function destroy(int $id)
    {
        ClassModel::findOrFail($id)->delete();
        return redirect()->route('classes.index')->with('success', 'Class deleted successfully');
    }

    public function sections(int $classId)
    {
        $class = ClassModel::findOrFail($classId);
        $sections = Section::where('class_id', $classId)->orderBy('name')->get();
        return view('sections.index', compact('sections', 'class'));
    }
}
