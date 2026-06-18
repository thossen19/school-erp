<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Academic\ClassModel;
use App\Models\Academic\Subject;
use App\Models\Assessment\ContinuousAssessment;
use App\Models\Assessment\Exam;
use App\Models\Assessment\ExamResult;
use App\Models\Assessment\ExamType;
use App\Models\Assessment\GradeRange;
use App\Models\Assessment\GradingSystem;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function index(Request $request)
    {
        $exams = Exam::with('examType')->withCount('schedules', 'results')
            ->when($request->exam_type, fn($q) => $q->where('exam_type_id', $request->exam_type))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderBy('start_date', 'desc')->paginate(15);

        return view('assessments.index', compact('exams'));
    }

    public function create()
    {
        $examTypes = ExamType::active()->orderBy('name')->get();
        $classes = ClassModel::active()->orderBy('name')->get();
        return view('assessments.create', compact('examTypes', 'classes'));
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
        return redirect()->route('assessment.index')->with('success', 'Exam created successfully');
    }

    public function show(int $id)
    {
        $exam = Exam::with('schedules.class', 'schedules.subject', 'results.student')->withCount('schedules', 'results')->findOrFail($id);
        return view('assessments.show', compact('exam'));
    }

    public function edit(int $id)
    {
        $exam = Exam::findOrFail($id);
        $examTypes = ExamType::active()->orderBy('name')->get();
        $classes = ClassModel::active()->orderBy('name')->get();
        return view('assessments.edit', compact('exam', 'examTypes', 'classes'));
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
        return redirect()->route('assessment.index')->with('success', 'Exam updated successfully');
    }

    public function destroy(int $id)
    {
        Exam::findOrFail($id)->delete();
        return redirect()->route('assessment.index')->with('success', 'Exam deleted successfully');
    }

    public function results(Request $request)
    {
        $query = ExamResult::with('exam', 'student', 'subject');

        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('student', fn($q) => $q->where('first_name', 'like', "%{$request->search}%")
                ->orWhere('last_name', 'like', "%{$request->search}%")
                ->orWhere('admission_no', 'like', "%{$request->search}%"));
        }

        $results = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => ExamResult::count(),
            'passed' => ExamResult::where('status', 'passed')->count(),
            'failed' => ExamResult::where('status', 'failed')->count(),
            'avg_percentage' => round(ExamResult::avg('percentage'), 1),
        ];

        $exams = Exam::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('assessments.results', compact('results', 'stats', 'exams', 'subjects'));
    }

    public function grading()
    {
        $gradingSystems = GradingSystem::with('gradeRanges')->orderBy('name')->get();

        return view('assessments.grading', compact('gradingSystems'));
    }

    public function continuous(Request $request)
    {
        $query = ContinuousAssessment::with('student', 'subject');

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('assessment_type')) {
            $query->where('assessment_type', $request->assessment_type);
        }

        if ($request->filled('search')) {
            $query->whereHas('student', fn($q) => $q->where('first_name', 'like', "%{$request->search}%")
                ->orWhere('last_name', 'like', "%{$request->search}%"));
        }

        $assessments = $query->orderBy('assessment_date', 'desc')->paginate(15);

        $assessments->getCollection()->transform(function ($ca) {
            $ca->percentage = $ca->max_marks > 0 ? round(($ca->marks_obtained / $ca->max_marks) * 100, 1) : 0;
            $pct = $ca->percentage;
            $ca->grade = $pct >= 90 ? 'A+' : ($pct >= 75 ? 'A' : ($pct >= 60 ? 'B' : ($pct >= 50 ? 'C' : ($pct >= 35 ? 'D' : 'F'))));
            return $ca;
        });

        $subjects = Subject::orderBy('name')->get();
        $types = ContinuousAssessment::distinct('assessment_type')->pluck('assessment_type');

        return view('assessments.continuous', compact('assessments', 'subjects', 'types'));
    }
}
