<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Academic\Homework;
use Illuminate\Http\Request;

class HomeworkController extends Controller
{
    public function index()
    {
        $homeworks = Homework::with('class', 'subject', 'teacher')->orderBy('created_at', 'desc')->paginate(15);
        return view('academics.homework', compact('homeworks'));
    }

    public function create()
    {
        return view('academics.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|integer|exists:classes,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'due_date' => 'required|date|after:today',
            'description' => 'nullable|string',
        ]);
        $validated['created_by'] = $request->user()->id;
        $validated['assigned_date'] = now();
        Homework::create($validated);
        return redirect()->route('homework.index')->with('success', 'Homework assigned');
    }

    public function show(int $id)
    {
        $homework = Homework::with('class', 'subject', 'teacher')->findOrFail($id);
        return view('academics.show', compact('homework'));
    }

    public function edit(int $id)
    {
        $homework = Homework::findOrFail($id);
        return view('academics.edit', compact('homework'));
    }

    public function update(Request $request, int $id)
    {
        $homework = Homework::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'due_date' => 'sometimes|date|after:today',
            'description' => 'nullable|string',
        ]);
        $homework->update($validated);
        return redirect()->route('homework.index')->with('success', 'Homework updated');
    }

    public function destroy(int $id)
    {
        Homework::findOrFail($id)->delete();
        return redirect()->route('homework.index')->with('success', 'Homework deleted');
    }
}
