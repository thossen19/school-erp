<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Hr\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('employees')->orderBy('name')->paginate(20);
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:departments',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        Department::create($validated);
        return redirect()->route('departments.index')->with('success', 'Department created successfully');
    }

    public function show(int $id)
    {
        $department = Department::with('employees')->withCount('employees')->findOrFail($id);
        return view('departments.show', compact('department'));
    }

    public function edit(int $id)
    {
        $department = Department::findOrFail($id);
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, int $id)
    {
        $department = Department::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'code' => 'sometimes|string|max:20|unique:departments,code,' . $id,
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);
        $department->update($validated);
        return redirect()->route('departments.index')->with('success', 'Department updated successfully');
    }

    public function destroy(int $id)
    {
        Department::findOrFail($id)->delete();
        return redirect()->route('departments.index')->with('success', 'Department deleted successfully');
    }
}
