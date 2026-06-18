<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Academic\ClassModel;
use App\Models\Attendance\Attendance;
use App\Models\Fee\FeeCollection;
use App\Models\Hr\Employee;
use App\Models\Student\Student;
use App\Services\Mis\MisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    protected MisService $misService;

    public function __construct(MisService $misService)
    {
        $this->misService = $misService;
    }

    public function index()
    {
        return view('reports.index');
    }

    public function studentReport(Request $request)
    {
        $students = Student::with('class', 'section')->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))->when($request->section_id, fn($q) => $q->where('section_id', $request->section_id))->get();

        $total = $students->count();
        $byGender = $students->groupBy('gender')->map->count();
        $classes = ClassModel::active()->orderBy('name')->get();

        return view('reports.student', compact('students', 'total', 'byGender', 'classes'));
    }

    public function attendanceReport(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        $summary = Attendance::selectRaw('status, COUNT(*) as count')->whereBetween('date', [$request->date_from, $request->date_to])->groupBy('status')->get();

        $records = Attendance::with('student:id,first_name,last_name', 'class', 'section')->whereBetween('date', [$request->date_from, $request->date_to])->paginate(50);

        return view('reports.attendance', compact('summary', 'records', 'request'));
    }

    public function feeReport(Request $request)
    {
        $summary = FeeCollection::selectRaw('YEAR(payment_date) as year, MONTH(payment_date) as month, SUM(paid_amount) as total')->when($request->year, fn($q) => $q->whereYear('payment_date', $request->year))->groupBy('year', 'month')->orderBy('year', 'desc')->orderBy('month', 'desc')->get();

        $totalCollected = FeeCollection::when($request->year, fn($q) => $q->whereYear('payment_date', $request->year))->sum('paid_amount');

        return view('reports.fee', compact('summary', 'totalCollected', 'request'));
    }

    public function employeeReport(Request $request)
    {
        $employees = Employee::with('department', 'designation')->get()->groupBy('department.name');

        $total = Employee::count();
        $byType = Employee::selectRaw('employment_type, COUNT(*) as count')->groupBy('employment_type')->get();

        return view('reports.employee', compact('employees', 'total', 'byType'));
    }
}
