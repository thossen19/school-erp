<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentGroup;
use Illuminate\Http\Request;

class StudentGroupController extends Controller
{
    public function index()
    {
        $groups = StudentGroup::withCount('students')->latest()->paginate(15);
        return view('student-groups.index', compact('groups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:student_groups,code',
            'description' => 'nullable|string|max:1000',
            'status' => 'boolean',
        ]);
        StudentGroup::create($validated);
        return redirect()->route('student-groups.index')->with('success', 'Group created successfully');
    }

    public function update(Request $request, int $id)
    {
        $group = StudentGroup::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:student_groups,code,' . $id,
            'description' => 'nullable|string|max:1000',
            'status' => 'boolean',
        ]);
        $group->update($validated);
        return redirect()->route('student-groups.index')->with('success', 'Group updated successfully');
    }

    public function destroy(int $id)
    {
        StudentGroup::findOrFail($id)->delete();
        return redirect()->route('student-groups.index')->with('success', 'Group deleted successfully');
    }
}
