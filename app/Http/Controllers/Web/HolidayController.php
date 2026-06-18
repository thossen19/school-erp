<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Attendance\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index(Request $request)
    {
        $holidays = Holiday::when($request->year, fn($q) => $q->whereYear('date', $request->year))->when($request->type, fn($q) => $q->where('type', $request->type))->orderBy('date')->paginate(20);

        return view('holidays.index', compact('holidays'));
    }

    public function create()
    {
        return view('holidays.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|string|in:public,religious,school,exam,other',
            'description' => 'nullable|string|max:1000',
            'is_optional' => 'boolean',
        ]);

        Holiday::create($validated);
        return redirect()->route('holidays.index')->with('success', 'Holiday created successfully');
    }

    public function show(int $id)
    {
        $holiday = Holiday::findOrFail($id);
        return view('holidays.show', compact('holiday'));
    }

    public function edit(int $id)
    {
        $holiday = Holiday::findOrFail($id);
        return view('holidays.edit', compact('holiday'));
    }

    public function update(Request $request, int $id)
    {
        $holiday = Holiday::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'date' => 'sometimes|date',
            'type' => 'sometimes|string|in:public,religious,school,exam,other',
            'description' => 'nullable|string|max:1000',
            'is_optional' => 'boolean',
        ]);
        $holiday->update($validated);
        return redirect()->route('holidays.index')->with('success', 'Holiday updated successfully');
    }

    public function destroy(int $id)
    {
        Holiday::findOrFail($id)->delete();
        return redirect()->route('holidays.index')->with('success', 'Holiday deleted successfully');
    }
}
