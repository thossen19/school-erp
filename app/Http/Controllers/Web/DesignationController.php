<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Hr\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index()
    {
        $designations = Designation::withCount('employees')->orderBy('name')->paginate(20);
        return view('designations.index', compact('designations'));
    }

    public function create()
    {
        return view('designations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        Designation::create($validated);
        return redirect()->route('designations.index')->with('success', 'Designation created successfully');
    }

    public function show(int $id)
    {
        $designation = Designation::with('employees')->withCount('employees')->findOrFail($id);
        return view('designations.show', compact('designation'));
    }

    public function edit(int $id)
    {
        $designation = Designation::findOrFail($id);
        return view('designations.edit', compact('designation'));
    }

    public function update(Request $request, int $id)
    {
        $designation = Designation::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);
        $designation->update($validated);
        return redirect()->route('designations.index')->with('success', 'Designation updated successfully');
    }

    public function destroy(int $id)
    {
        Designation::findOrFail($id)->delete();
        return redirect()->route('designations.index')->with('success', 'Designation deleted successfully');
    }
}
