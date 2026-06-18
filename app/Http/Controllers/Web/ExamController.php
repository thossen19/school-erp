<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Academic\ClassModel;
use App\Models\Assessment\Exam;
use App\Models\Assessment\ExamSchedule;
use App\Services\Assessment\ExamService;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    protected ExamService $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
    }

    public function index(Request $request)
    {
        $exams = Exam::with('examType')->withCount('schedules', 'results')
            ->when($request->exam_type, fn($q) => $q->where('exam_type_id', $request->exam_type))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderBy('start_date', 'desc')->paginate(15);

        return view('exams.index', compact('exams'));
    }

    public function create()
    {
        $classes = ClassModel::active()->orderBy('name')->get();
        return view('exams.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'exam_type' => 'required|string|in:midterm,final,quarterly,half_yearly,unit_test,pre_board,other',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'nullable|string|max:1000',
        ]);

        Exam::create($validated);
        return redirect()->route('exams.index')->with('success', 'Exam created successfully');
    }

    public function show(int $id)
    {
        $exam = Exam::with('schedules.class', 'schedules.subject', 'results')->withCount('schedules', 'results')->findOrFail($id);
        return view('exams.show', compact('exam'));
    }

    public function edit(int $id)
    {
        $exam = Exam::findOrFail($id);
        $classes = ClassModel::active()->orderBy('name')->get();
        return view('exams.edit', compact('exam', 'classes'));
    }

    public function update(Request $request, int $id)
    {
        $exam = Exam::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'exam_type' => 'sometimes|string|in:midterm,final,quarterly,half_yearly,unit_test,pre_board,other',
            'start_date' => 'sometimes|date|before_or_equal:end_date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'description' => 'nullable|string|max:1000',
        ]);
        $exam->update($validated);
        return redirect()->route('exams.index')->with('success', 'Exam updated successfully');
    }

    public function destroy(int $id)
    {
        Exam::findOrFail($id)->delete();
        return redirect()->route('exams.index')->with('success', 'Exam deleted successfully');
    }

    public function schedule(int $id)
    {
        $exam = Exam::findOrFail($id);
        $schedules = ExamSchedule::with('class', 'subject')->where('exam_id', $id)->orderBy('exam_date')->get();
        return view('exams.schedule', compact('exam', 'schedules'));
    }

    public function results(int $id)
    {
        $exam = Exam::with('results.student', 'results.subject')->withCount('results')->findOrFail($id);
        return view('exams.results', compact('exam'));
    }
}
