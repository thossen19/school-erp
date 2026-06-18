<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AIController extends Controller
{
    public function chat()
    {
        $messages = DB::table('ai_chat_messages')->where('school_id', 1)->where('user_id', auth()->id())->orderBy('created_at', 'desc')->paginate(50);
        return view('ai.chat', compact('messages'));
    }

    public function storeChat(Request $request)
    {
        $validated = $request->validate(['message' => 'required|string|max:5000']);
        $validated['school_id'] = 1;
        $validated['user_id'] = auth()->id();
        $validated['response'] = 'This is a simulated AI response. In production, this would connect to an AI API (e.g., OpenAI, Claude, or a fine-tuned model). Your query: ' . $validated['message'];
        DB::table('ai_chat_messages')->insert($validated);
        return redirect()->route('ai.chat')->with('success', 'Response generated');
    }

    public function performancePrediction(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('ai_predictions')->where('ai_predictions.school_id', $schoolId)->where('type', 'performance')
            ->leftJoin('students', function ($join) { $join->on('ai_predictions.predictable_id', '=', 'students.id')->where('ai_predictions.predictable_type', '=', 'App\\Models\\Student'); })
            ->select('ai_predictions.*', 'students.first_name', 'students.last_name', 'students.admission_no');
        if ($request->filled('class_id')) $query->where('students.class_id', $request->class_id);
        $predictions = $query->orderBy('ai_predictions.created_at', 'desc')->paginate(15);
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();
        $stats = DB::table('ai_predictions')->where('school_id', $schoolId)->where('type', 'performance')->select(DB::raw('AVG(prediction_score) as avg_score'), DB::raw('COUNT(*) as total'))->first();
        return view('ai.performance-prediction', compact('predictions', 'classes', 'stats'));
    }

    public function attendancePrediction(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('ai_predictions')->where('ai_predictions.school_id', $schoolId)->where('type', 'attendance')
            ->leftJoin('students', function ($join) { $join->on('ai_predictions.predictable_id', '=', 'students.id')->where('ai_predictions.predictable_type', '=', 'App\\Models\\Student'); })
            ->select('ai_predictions.*', 'students.first_name', 'students.last_name', 'students.admission_no');
        if ($request->filled('class_id')) $query->where('students.class_id', $request->class_id);
        $predictions = $query->orderBy('ai_predictions.created_at', 'desc')->paginate(15);
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();
        $stats = DB::table('ai_predictions')->where('school_id', $schoolId)->where('type', 'attendance')->select(DB::raw('AVG(prediction_score) as avg_score'), DB::raw('COUNT(*) as total'))->first();
        return view('ai.attendance-prediction', compact('predictions', 'classes', 'stats'));
    }

    public function feeDefaulterPrediction(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('ai_predictions')->where('ai_predictions.school_id', $schoolId)->where('type', 'fee_defaulter')
            ->leftJoin('students', function ($join) { $join->on('ai_predictions.predictable_id', '=', 'students.id')->where('ai_predictions.predictable_type', '=', 'App\\Models\\Student'); })
            ->select('ai_predictions.*', 'students.first_name', 'students.last_name', 'students.admission_no');
        if ($request->filled('class_id')) $query->where('students.class_id', $request->class_id);
        $predictions = $query->orderBy('ai_predictions.created_at', 'desc')->paginate(15);
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();
        $stats = DB::table('ai_predictions')->where('school_id', $schoolId)->where('type', 'fee_defaulter')->select(DB::raw('AVG(prediction_score) as avg_score'), DB::raw('COUNT(*) as total'))->first();
        return view('ai.fee-defaulter-prediction', compact('predictions', 'classes', 'stats'));
    }

    public function reportGenerator(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('ai_reports')->where('ai_reports.school_id', $schoolId)
            ->leftJoin('users', 'ai_reports.generated_by', '=', 'users.id')
            ->select('ai_reports.*', 'users.name as generated_by_name');
        if ($request->filled('type')) $query->where('ai_reports.type', $request->type);
        $reports = $query->orderBy('ai_reports.created_at', 'desc')->paginate(15);
        return view('ai.report-generator', compact('reports'));
    }

    public function generateReport(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'parameters' => 'nullable|json',
        ]);
        $validated['school_id'] = 1;
        $validated['generated_by'] = auth()->id();
        $validated['status'] = 'completed';
        $validated['result'] = json_encode(['message' => 'AI report generation simulated. In production, this would call an AI model to analyze data and generate insights.']);
        DB::table('ai_reports')->insert($validated);
        return redirect()->route('ai.report-generator')->with('success', 'Report generated');
    }

    public function timetableGenerator()
    {
        $schoolId = 1;
        $classes = DB::table('classes')->where('school_id', $schoolId)->orderBy('name')->get();
        $teachers = DB::table('employees')->where('school_id', $schoolId)->where('status', 'active')->orderBy('first_name')->get();
        $existing = DB::table('timetables')->where('school_id', $schoolId)->count();
        return view('ai.timetable-generator', compact('classes', 'teachers', 'existing'));
    }

    public function generateTimetable(Request $request)
    {
        $request->validate(['class_id' => 'required|integer|exists:classes,id']);
        return redirect()->route('ai.timetable-generator')->with('success', 'AI timetable generation initiated. In production, this would use constraint satisfaction algorithms to generate an optimal timetable.');
    }

    public function analyticsDashboard()
    {
        $schoolId = 1;
        $totalStudents = DB::table('students')->where('school_id', $schoolId)->count();
        $totalTeachers = DB::table('employees')->where('school_id', $schoolId)->where('employment_type', 'teacher')->count();
        $avgAttendance = DB::table('attendances')->where('school_id', $schoolId)->whereDate('date', now()->toDateString())->avg('status');
        $feeCollection = DB::table('fee_collections')->where('school_id', $schoolId)->whereMonth('payment_date', now()->month)->sum('paid_amount');
        $predictions = DB::table('ai_predictions')->where('school_id', $schoolId)->select('type', DB::raw('AVG(prediction_score) as avg_score'), DB::raw('COUNT(*) as total'))->groupBy('type')->get();
        $recentReports = DB::table('ai_reports')->where('school_id', $schoolId)->orderBy('created_at', 'desc')->take(5)->get();
        return view('ai.analytics-dashboard', compact('totalStudents', 'totalTeachers', 'avgAttendance', 'feeCollection', 'predictions', 'recentReports'));
    }

    public function recommendationEngine(Request $request)
    {
        $schoolId = 1;
        $query = DB::table('ai_recommendations')->where('school_id', $schoolId);
        if ($request->filled('type')) $query->where('type', $request->type);
        $recommendations = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('ai.recommendation-engine', compact('recommendations'));
    }

    public function storeRecommendation(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:50',
            'recommendation' => 'required|string|max:5000',
            'confidence' => 'nullable|numeric|min:0|max:100',
        ]);
        $validated['school_id'] = 1;
        $validated['status'] = 'active';
        $validated['recommendable_type'] = 'App\Models\Student';
        $validated['recommendable_id'] = 0;
        DB::table('ai_recommendations')->insert($validated);
        return redirect()->route('ai.recommendation-engine')->with('success', 'Recommendation added');
    }
}
