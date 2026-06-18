<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentTransfer;
use Illuminate\Http\Request;

class StudentTransferController extends Controller
{
    public function index()
    {
        $transfers = StudentTransfer::with('student')->latest()->paginate(15);
        return view('student-transfers.index', compact('transfers'));
    }

    public function create()
    {
        return view('student-transfers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'transfer_date' => 'required|date',
        ]);
        StudentTransfer::create($validated);
        return redirect()->route('student-transfers.index')->with('success', 'Created successfully');
    }

    public function show(int $id)
    {
        $transfer = StudentTransfer::with('student')->findOrFail($id);
        return view('student-transfers.show', compact('transfer'));
    }

    public function edit(int $id)
    {
        $transfer = StudentTransfer::findOrFail($id);
        return view('student-transfers.edit', compact('transfer'));
    }

    public function update(Request $request, int $id)
    {
        $transfer = StudentTransfer::findOrFail($id);
        $transfer->update($request->all());
        return redirect()->route('student-transfers.index')->with('success', 'Updated successfully');
    }

    public function destroy(int $id)
    {
        StudentTransfer::findOrFail($id)->delete();
        return redirect()->route('student-transfers.index')->with('success', 'Deleted successfully');
    }
}
