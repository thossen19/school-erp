<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Academic\LessonPlan;
use Illuminate\Http\Request;

class LessonPlanController extends Controller
{
    public function index()
    {
        $lessonPlans = LessonPlan::with('class', 'subject', 'teacher')->orderBy('created_at', 'desc')->paginate(15);
        return view('academics.lesson-plans', compact('lessonPlans'));
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
            'content' => 'required|string',
            'duration_minutes' => 'nullable|integer|min:5',
        ]);
        $validated['teacher_id'] = $request->user()->id;
        LessonPlan::create($validated);
        return redirect()->route('lesson-plans.index')->with('success', 'Lesson plan created');
    }

    public function show(int $id)
    {
        $lessonPlan = LessonPlan::with('class', 'subject', 'teacher')->findOrFail($id);
        return view('academics.show', compact('lessonPlan'));
    }

    public function edit(int $id)
    {
        $lessonPlan = LessonPlan::findOrFail($id);
        return view('academics.edit', compact('lessonPlan'));
    }

    public function update(Request $request, int $id)
    {
        $lessonPlan = LessonPlan::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'class_id' => 'sometimes|integer|exists:classes,id',
            'subject_id' => 'sometimes|integer|exists:subjects,id',
            'content' => 'sometimes|string',
            'duration_minutes' => 'nullable|integer|min:5',
        ]);
        $lessonPlan->update($validated);
        return redirect()->route('lesson-plans.index')->with('success', 'Lesson plan updated');
    }

    public function destroy(int $id)
    {
        LessonPlan::findOrFail($id)->delete();
        return redirect()->route('lesson-plans.index')->with('success', 'Lesson plan deleted');
    }
}
