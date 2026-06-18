<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Academic\ClassModel;
use App\Models\Academic\Subject;
use App\Models\Assessment\AiEvaluation;
use App\Models\Assessment\ContinuousAssessment;
use App\Models\Assessment\Exam;
use App\Models\Assessment\ExamResult;
use App\Models\Assessment\ExamSchedule;
use App\Models\Assessment\ExamType;
use App\Models\Assessment\GradeRange;
use App\Models\Assessment\GradingSystem;
use App\Models\Assessment\OnlineExamination;
use App\Models\Assessment\PracticalMark;
use App\Models\Student\Student;
use App\Models\Academic\AssignmentSubmission;
use App\Models\Academic\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessmentManageController extends Controller
{
    public function examSetup(Request $request)
    {
        $exams = Exam::with('examType')
            ->withCount('schedules', 'results')
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->exam_type_id, fn($q) => $q->where('exam_type_id', $request->exam_type_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderBy('start_date', 'desc')->paginate(20);

        $examTypes = ExamType::active()->orderBy('name')->get();
        return view('assessment-manage.exam-setup', compact('exams', 'examTypes'));
    }

    public function gradingSystem(Request $request)
    {
        $gradingSystems = GradingSystem::with('gradeRanges')
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->orderBy('name')->paginate(20);

        return view('assessment-manage.grading-system', compact('gradingSystems'));
    }

    public function subjectMarks(Request $request)
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

        $results = $query->orderBy('created_at', 'desc')->paginate(20);
        $exams = Exam::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('assessment-manage.subject-marks', compact('results', 'exams', 'subjects'));
    }

    public function practicalMarks(Request $request)
    {
        $query = PracticalMark::with('student', 'subject', 'exam');

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->whereHas('student', fn($q) => $q->where('first_name', 'like', "%{$request->search}%")
                ->orWhere('last_name', 'like', "%{$request->search}%"));
        }

        $marks = $query->orderBy('practical_date', 'desc')->paginate(20);
        $subjects = Subject::orderBy('name')->get();
        $exams = Exam::orderBy('name')->get();

        return view('assessment-manage.practical-marks', compact('marks', 'subjects', 'exams'));
    }

    public function assignmentMarks(Request $request)
    {
        $query = AssignmentSubmission::with('assignment', 'student');

        if ($request->filled('assignment_id')) {
            $query->where('assignment_id', $request->assignment_id);
        }
        if ($request->filled('search')) {
            $query->whereHas('student', fn($q) => $q->where('first_name', 'like', "%{$request->search}%")
                ->orWhere('last_name', 'like', "%{$request->search}%")
                ->orWhere('admission_no', 'like', "%{$request->search}%"));
        }

        $submissions = $query->whereNotNull('marks')->orderBy('created_at', 'desc')->paginate(20);
        $assignments = Assignment::orderBy('title')->get(['id', 'title']);

        return view('assessment-manage.assignment-marks', compact('submissions', 'assignments'));
    }

    public function continuousAssessment(Request $request)
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

        $assessments = $query->orderBy('assessment_date', 'desc')->paginate(20);
        $subjects = Subject::orderBy('name')->get();
        $types = ContinuousAssessment::distinct()->pluck('assessment_type');

        return view('assessment-manage.continuous-assessment', compact('assessments', 'subjects', 'types'));
    }

    public function onlineExamination(Request $request)
    {
        $query = OnlineExamination::with('class', 'subject');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        $exams = $query->orderBy('created_at', 'desc')->paginate(20);
        $subjects = Subject::orderBy('name')->get();

        return view('assessment-manage.online-examination', compact('exams', 'subjects'));
    }

    public function aiEvaluation(Request $request)
    {
        $query = AiEvaluation::with('student', 'subject');

        if ($request->filled('evaluation_type')) {
            $query->where('evaluation_type', $request->evaluation_type);
        }
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('search')) {
            $query->whereHas('student', fn($q) => $q->where('first_name', 'like', "%{$request->search}%")
                ->orWhere('last_name', 'like', "%{$request->search}%"));
        }

        $evaluations = $query->orderBy('created_at', 'desc')->paginate(20);
        $evaluationTypes = AiEvaluation::select('evaluation_type')->distinct()->pluck('evaluation_type');
        $subjects = Subject::orderBy('name')->get();

        return view('assessment-manage.ai-evaluation', compact('evaluations', 'evaluationTypes', 'subjects'));
    }

    public function resultProcessing(Request $request)
    {
        $exams = Exam::with('examType')->withCount('results')
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->orderBy('start_date', 'desc')->paginate(20);

        $stats = [
            'total_exams' => Exam::count(),
            'total_results' => ExamResult::count(),
            'avg_percentage' => round(ExamResult::avg('percentage'), 1),
            'passed' => ExamResult::where('status', 'passed')->count(),
            'failed' => ExamResult::where('status', 'failed')->count(),
        ];

        $byExam = ExamResult::select('exam_id', DB::raw('count(*) as total'), DB::raw('avg(percentage) as avg_pct'))
            ->groupBy('exam_id')
            ->with('exam')
            ->get();

        return view('assessment-manage.result-processing', compact('exams', 'stats', 'byExam'));
    }

    public function ranking(Request $request)
    {
        $query = ExamResult::select('student_id', DB::raw('avg(percentage) as avg_percentage'),
            DB::raw('count(*) as exams_attempted'), DB::raw('sum(case when status = "passed" then 1 else 0 end) as passed'),
            DB::raw('sum(case when status = "failed" then 1 else 0 end) as failed'))
            ->with('student')
            ->groupBy('student_id')
            ->orderBy('avg_percentage', 'desc');

        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }
        if ($request->filled('search')) {
            $query->whereHas('student', fn($q) => $q->where('first_name', 'like', "%{$request->search}%")
                ->orWhere('last_name', 'like', "%{$request->search}%")
                ->orWhere('admission_no', 'like', "%{$request->search}%"));
        }

        $rankings = $query->paginate(30);
        $exams = Exam::orderBy('name')->get();

        return view('assessment-manage.ranking', compact('rankings', 'exams'));
    }

    public function performanceAnalytics()
    {
        $totalStudents = Student::count();
        $totalExams = Exam::count();
        $totalResults = ExamResult::count();
        $overallAvg = round(ExamResult::avg('percentage'), 1);

        $passFailStats = ExamResult::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')->get();

        $avgBySubject = ExamResult::select('subject_id', DB::raw('avg(percentage) as avg_pct'), DB::raw('count(*) as total'))
            ->whereNotNull('subject_id')
            ->groupBy('subject_id')
            ->with('subject')
            ->orderBy('avg_pct', 'desc')
            ->get();

        $avgByExam = ExamResult::select('exam_id', DB::raw('avg(percentage) as avg_pct'), DB::raw('count(*) as total'))
            ->groupBy('exam_id')
            ->with('exam')
            ->orderBy('avg_pct', 'desc')
            ->get();

        $topPerformers = ExamResult::select('student_id', DB::raw('avg(percentage) as avg_pct'))
            ->groupBy('student_id')
            ->with('student')
            ->orderBy('avg_pct', 'desc')
            ->take(10)
            ->get();

        $monthlyTrend = ExamResult::select(
            DB::raw('YEAR(created_at) as year'), DB::raw('MONTH(created_at) as month'),
            DB::raw('count(*) as total'), DB::raw('avg(percentage) as avg_pct'))
            ->groupBy('year', 'month')
            ->orderBy('year')->orderBy('month')
            ->take(12)
            ->get();

        return view('assessment-manage.performance-analytics', compact(
            'totalStudents', 'totalExams', 'totalResults', 'overallAvg',
            'passFailStats', 'avgBySubject', 'avgByExam', 'topPerformers', 'monthlyTrend'
        ));
    }
}
