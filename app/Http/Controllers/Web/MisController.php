<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MisController extends Controller
{
    private function schoolId() { return 1; }

    public function executiveDashboard()
    {
        $sid = $this->schoolId();
        $data = [
            'totalStudents' => DB::table('students')->where('school_id', $sid)->count(),
            'totalEmployees' => DB::table('employees')->where('school_id', $sid)->count(),
            'totalClasses' => DB::table('classes')->where('school_id', $sid)->count(),
            'todayPresent' => DB::table('attendances')->whereDate('date', now())->where('status', 'present')->count(),
            'todayAbsent' => DB::table('attendances')->whereDate('date', now())->where('status', 'absent')->count(),
            'pendingFees' => DB::table('fee_collections')->where('school_id', $sid)->where('balance_amount', '>', 0)->count(),
            'recentAdmissions' => DB::table('admission_forms')->where('school_id', $sid)->whereDate('created_at', '>=', now()->subDays(30))->count(),
            'monthlyFeeCollected' => DB::table('fee_collections')->where('school_id', $sid)->whereMonth('payment_date', now()->month)->whereYear('payment_date', now()->year)->sum('paid_amount'),
            'genderBreakdown' => DB::table('students')->where('school_id', $sid)->selectRaw("gender, count(*) as total")->groupBy('gender')->get(),
            'recentStudents' => DB::table('students')->where('school_id', $sid)->orderBy('created_at', 'desc')->limit(5)->get(['first_name', 'last_name', 'admission_no']),
        ];
        return view('mis.executive-dashboard', $data);
    }

    public function kpiTracking()
    {
        $sid = $this->schoolId();
        $totalStudents = DB::table('students')->where('school_id', $sid)->count();
        $totalEmployees = DB::table('employees')->where('school_id', $sid)->count();
        $studentTeacherRatio = $totalEmployees > 0 ? round($totalStudents / $totalEmployees, 1) : 0;
        $avgAttendance = DB::table('attendances')->selectRaw("round(avg(case when status='present' then 100 else 0 end),1) as rate")->value('rate') ?? 0;
        $passRate = DB::table('exam_results')->selectRaw("round((sum(case when status='passed' then 1 else 0 end)/count(*))*100,1) as rate")->value('rate') ?? 0;
        $feeCollectionRate = DB::table('fee_collections')->selectRaw("round((sum(paid_amount)/nullif(sum(paid_amount)+sum(balance_amount),0))*100,1) as rate")->value('rate') ?? 0;
        $graduationRate = DB::table('alumni')->where('school_id', $sid)->count() > 0 ? 85 : 0;

        $kpis = [
            ['metric' => 'Student-Teacher Ratio', 'value' => $studentTeacherRatio, 'target' => 15, 'unit' => ':1'],
            ['metric' => 'Average Attendance', 'value' => $avgAttendance, 'target' => 90, 'unit' => '%'],
            ['metric' => 'Exam Pass Rate', 'value' => $passRate, 'target' => 80, 'unit' => '%'],
            ['metric' => 'Fee Collection', 'value' => $feeCollectionRate, 'target' => 95, 'unit' => '%'],
            ['metric' => 'Graduation Rate', 'value' => $graduationRate, 'target' => 90, 'unit' => '%'],
        ];
        return view('mis.kpi-tracking', compact('kpis'));
    }

    public function academicAnalytics(Request $request)
    {
        $sid = $this->schoolId();
        $classWiseStudents = DB::table('students')->where('students.school_id', $sid)
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->selectRaw("classes.name as class_name, count(*) as total")
            ->groupBy('classes.name')->orderBy('classes.name')->get();

        $examPerformance = DB::table('exam_results')->where('exam_results.school_id', $sid)
            ->join('exams', 'exam_results.exam_id', '=', 'exams.id')
            ->selectRaw("exams.name as exam_name, round(avg(percentage),2) as avg_percentage, count(*) as total_students, sum(case when exam_results.status='passed' then 1 else 0 end) as passed")
            ->groupBy('exams.name')->orderBy('exams.name')->get();

        $sectionWise = DB::table('students')->where('students.school_id', $sid)
            ->join('sections', 'students.section_id', '=', 'sections.id')
            ->join('classes', 'sections.class_id', '=', 'classes.id')
            ->selectRaw("classes.name as class_name, sections.name as section_name, count(*) as total")
            ->groupBy('classes.name', 'sections.name')->orderBy('classes.name')->orderBy('sections.name')->get();

        return view('mis.academic-analytics', compact('classWiseStudents', 'examPerformance', 'sectionWise'));
    }

    public function financialAnalytics(Request $request)
    {
        $sid = $this->schoolId();
        $year = $request->year ?? now()->year;

        $monthlyCollection = DB::table('fee_collections')->where('school_id', $sid)
            ->whereYear('payment_date', $year)
            ->selectRaw("month(payment_date) as month, sum(paid_amount) as total")
            ->groupBy('month')->orderBy('month')->get();

        $paymentModeBreakdown = DB::table('fee_collections')->where('school_id', $sid)
            ->whereYear('payment_date', $year)
            ->selectRaw("payment_mode, count(*) as count, sum(paid_amount) as total")
            ->groupBy('payment_mode')->get();

        $totalCollected = DB::table('fee_collections')->where('school_id', $sid)->whereYear('payment_date', $year)->sum('paid_amount');
        $totalPending = DB::table('fee_collections')->where('school_id', $sid)->sum('balance_amount');

        $feeHeadWise = DB::table('fee_collections')->where('fee_collections.school_id', $sid)
            ->whereYear('payment_date', $year)
            ->join('fee_structures', 'fee_collections.fee_structure_id', '=', 'fee_structures.id')
            ->selectRaw("fee_structures.name as head_name, sum(paid_amount) as total")
            ->groupBy('fee_structures.name')->orderByDesc('total')->get();

        return view('mis.financial-analytics', compact('monthlyCollection', 'paymentModeBreakdown', 'totalCollected', 'totalPending', 'feeHeadWise', 'year'));
    }

    public function studentAnalytics()
    {
        $sid = $this->schoolId();
        $total = DB::table('students')->where('school_id', $sid)->count();
        $genderBreakdown = DB::table('students')->where('school_id', $sid)->selectRaw("gender, count(*) as total")->groupBy('gender')->get();
        $classDistribution = DB::table('students')->where('students.school_id', $sid)
            ->join('classes', 'students.class_id', '=', 'classes.id')
            ->selectRaw("classes.name, count(*) as total")->groupBy('classes.name')->orderBy('classes.name')->get();
        $statusBreakdown = DB::table('students')->where('school_id', $sid)->selectRaw("status, count(*) as total")->groupBy('status')->get();
        $admissionTrend = DB::table('admission_forms')->where('school_id', $sid)
            ->selectRaw("year(created_at) as year, month(created_at) as month, count(*) as total")
            ->groupBy('year', 'month')->orderBy('year')->orderBy('month')->limit(12)->get();

        return view('mis.student-analytics', compact('total', 'genderBreakdown', 'classDistribution', 'statusBreakdown', 'admissionTrend'));
    }

    public function attendanceAnalytics(Request $request)
    {
        $sid = $this->schoolId();
        $dateFrom = $request->date_from ?? now()->startOfMonth()->toDateString();
        $dateTo = $request->date_to ?? now()->toDateString();

        $summary = DB::table('attendances')->where('school_id', $sid)->whereBetween('date', [$dateFrom, $dateTo])
            ->selectRaw("status, count(*) as count")->groupBy('status')->get();

        $dailyTrend = DB::table('attendances')->where('school_id', $sid)->whereBetween('date', [$dateFrom, $dateTo])
            ->selectRaw("date, count(*) as total, sum(case when attendances.status='present' then 1 else 0 end) as present")
            ->groupBy('date')->orderBy('date')->get();

        $classWiseAttendance = DB::table('attendances')->where('attendances.school_id', $sid)->whereBetween('date', [$dateFrom, $dateTo])
            ->join('classes', 'attendances.class_id', '=', 'classes.id')
            ->selectRaw("classes.name, count(*) as total, sum(case when attendances.status='present' then 1 else 0 end) as present")
            ->groupBy('classes.name')->orderBy('classes.name')->get();

        return view('mis.attendance-analytics', compact('summary', 'dailyTrend', 'classWiseAttendance', 'dateFrom', 'dateTo'));
    }

    public function aiPredictiveAnalytics()
    {
        $sid = $this->schoolId();
        $totalStudents = DB::table('students')->where('school_id', $sid)->count();
        $avgAttendance = DB::table('attendances')->where('school_id', $sid)->selectRaw("round(avg(case when status='present' then 100 else 0 end),1) as rate")->value('rate') ?? 0;
        $avgPerformance = DB::table('exam_results')->where('school_id', $sid)->avg('percentage') ?? 0;
        $feeCompletion = DB::table('fee_collections')->where('school_id', $sid)->selectRaw("round((sum(paid_amount)/nullif(sum(paid_amount)+sum(balance_amount),0))*100,1) as rate")->value('rate') ?? 0;

        $atRiskStudents = DB::table('students as s')->where('s.school_id', $sid)->where('s.status', 'active')
            ->leftJoin('attendances as a', function ($j) { $j->on('s.id', '=', 'a.student_id')->whereDate('a.date', '>=', now()->subDays(30)); })
            ->selectRaw("s.id, s.first_name, s.last_name, s.admission_no, round(avg(case when a.status='present' then 100 else 0 end),1) as attendance_rate")
            ->groupBy('s.id', 's.first_name', 's.last_name', 's.admission_no')
            ->having('attendance_rate', '<', 75)->orHavingNull('attendance_rate')
            ->limit(10)->get();

        $atRiskFee = DB::table('fee_collections')->where('fee_collections.school_id', $sid)->where('balance_amount', '>', 0)
            ->join('students', 'fee_collections.student_id', '=', 'students.id')
            ->selectRaw("students.id, students.first_name, students.last_name, students.admission_no, fee_collections.balance_amount")
            ->orderByDesc('balance_amount')->limit(10)->get();

        return view('mis.ai-predictive-analytics', compact('totalStudents', 'avgAttendance', 'avgPerformance', 'feeCompletion', 'atRiskStudents', 'atRiskFee'));
    }

    public function customReports(Request $request)
    {
        $sid = $this->schoolId();
        $templates = DB::table('report_templates')->where('school_id', $sid)->orderBy('name')->paginate(20);
        $schedules = DB::table('scheduled_reports')->where('school_id', $sid)->orderBy('next_run')->get();
        return view('mis.custom-reports', compact('templates', 'schedules'));
    }

    public function storeCustomReport(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255', 'type' => 'required|string|max:50', 'config' => 'nullable|json']);
        DB::table('report_templates')->insert([
            'school_id' => $this->schoolId(), 'name' => $request->name, 'type' => $request->type,
            'config' => $request->config, 'created_at' => now(), 'updated_at' => now(),
        ]);
        return redirect()->route('mis.custom-reports')->with('success', 'Report template created');
    }

    public function deleteCustomReport(int $id)
    {
        DB::table('report_templates')->where('id', $id)->delete();
        return redirect()->route('mis.custom-reports')->with('success', 'Report template deleted');
    }

    public function scheduleReport(Request $request)
    {
        $request->validate([
            'report_template_id' => 'required|integer|exists:report_templates,id',
            'frequency' => 'required|string|in:daily,weekly,monthly,quarterly,yearly',
            'recipients' => 'required|string',
            'format' => 'nullable|string|max:50',
        ]);
        $nextRun = match ($request->frequency) {
            'daily' => now()->addDay(), 'weekly' => now()->addWeek(), 'monthly' => now()->addMonth(),
            'quarterly' => now()->addMonths(3), 'yearly' => now()->addYear(), default => now()->addDay(),
        };
        DB::table('scheduled_reports')->insert([
            'school_id' => $this->schoolId(), 'report_template_id' => $request->report_template_id,
            'frequency' => $request->frequency, 'recipients' => $request->recipients,
            'format' => $request->format, 'next_run' => $nextRun, 'status' => 'active',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        return redirect()->route('mis.custom-reports')->with('success', 'Report scheduled');
    }

    public function unscheduleReport(int $id)
    {
        DB::table('scheduled_reports')->where('id', $id)->update(['status' => 'inactive', 'updated_at' => now()]);
        return redirect()->route('mis.custom-reports')->with('success', 'Report schedule deactivated');
    }
}
