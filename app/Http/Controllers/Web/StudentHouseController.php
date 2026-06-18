<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentHouse;
use Illuminate\Http\Request;

class StudentHouseController extends Controller
{
    public function index()
    {
        $houses = StudentHouse::withCount('students')->latest()->paginate(15);
        return view('student-houses.index', compact('houses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:student_houses,code',
            'color' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:100',
            'motto' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
            'status' => 'boolean',
        ]);
        StudentHouse::create($validated);
        return redirect()->route('student-houses.index')->with('success', 'House created successfully');
    }

    public function update(Request $request, int $id)
    {
        $house = StudentHouse::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:50|unique:student_houses,code,' . $id,
            'color' => 'nullable|string|max:20',
            'icon' => 'nullable|string|max:100',
            'motto' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
            'status' => 'boolean',
        ]);
        $house->update($validated);
        return redirect()->route('student-houses.index')->with('success', 'House updated successfully');
    }

    public function destroy(int $id)
    {
        StudentHouse::findOrFail($id)->delete();
        return redirect()->route('student-houses.index')->with('success', 'House deleted successfully');
    }
}
