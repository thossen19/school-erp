<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Timetable\RoomAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomAllocationController extends Controller
{
    public function index(Request $request)
    {
        $rooms = RoomAllocation::when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->status, fn($q) => $q->where('status', $request->status === 'active'))
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->orderBy('name')->paginate(20);

        return view('timetables.room-allocations.index', compact('rooms'));
    }

    public function create()
    {
        return view('timetables.room-allocations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:room_allocations,code',
            'type' => 'required|string|in:classroom,laboratory,library,office,auditorium,sports,music_room,art_room,other',
            'capacity' => 'required|integer|min:1',
            'building' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:50',
            'status' => 'boolean',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['school_id'] = session('school_id', 1);
        $validated['status'] = $request->boolean('status');

        DB::table('room_allocations')->insert($validated);
        return redirect()->route('timetable.room-allocation')->with('success', 'Room created successfully');
    }

    public function show(int $id)
    {
        $room = RoomAllocation::findOrFail($id);
        return view('timetables.room-allocations.show', compact('room'));
    }

    public function edit(int $id)
    {
        $room = RoomAllocation::findOrFail($id);
        return view('timetables.room-allocations.edit', compact('room'));
    }

    public function update(Request $request, int $id)
    {
        $room = RoomAllocation::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:50|unique:room_allocations,code,' . $id,
            'type' => 'sometimes|string|in:classroom,laboratory,library,office,auditorium,sports,music_room,art_room,other',
            'capacity' => 'sometimes|integer|min:1',
            'building' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:50',
            'status' => 'boolean',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['status'] = $request->boolean('status');
        DB::table('room_allocations')->where('id', $id)->update($validated);
        return redirect()->route('timetable.room-allocation')->with('success', 'Room updated successfully');
    }

    public function destroy(int $id)
    {
        DB::table('room_allocations')->where('id', $id)->delete();
        return redirect()->route('timetable.room-allocation')->with('success', 'Room deleted successfully');
    }
}
