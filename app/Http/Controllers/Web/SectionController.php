<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Academic\ClassModel;
use App\Models\Academic\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $sections = Section::with('class')->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))->orderBy('class_id')->orderBy('name')->paginate(20);

        $classes = ClassModel::active()->orderBy('name')->get();
        return view('sections.index', compact('sections', 'classes'));
    }

    public function create()
    {
        $classes = ClassModel::active()->orderBy('name')->get();
        return view('sections.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'class_id' => 'required|integer|exists:classes,id',
            'capacity' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        Section::create($validated);
        return redirect()->route('sections.index')->with('success', 'Section created successfully');
    }

    public function show(int $id)
    {
        $section = Section::with('class', 'students')->withCount('students')->findOrFail($id);
        return view('sections.show', compact('section'));
    }

    public function edit(int $id)
    {
        $section = Section::findOrFail($id);
        $classes = ClassModel::active()->orderBy('name')->get();
        return view('sections.edit', compact('section', 'classes'));
    }

    public function update(Request $request, int $id)
    {
        $section = Section::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'class_id' => 'sometimes|integer|exists:classes,id',
            'capacity' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);
        $section->update($validated);
        return redirect()->route('sections.index')->with('success', 'Section updated successfully');
    }

    public function destroy(int $id)
    {
        Section::findOrFail($id)->delete();
        return redirect()->route('sections.index')->with('success', 'Section deleted successfully');
    }
}
