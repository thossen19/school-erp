<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Hr\Department;
use App\Models\Hr\Designation;
use App\Models\Hr\Employee;
use App\Models\Hr\EmployeeDocument;
use App\Models\Hr\EmployeeEvaluation;
use App\Models\Hr\EmployeePromotion;
use App\Models\Hr\EmployeeTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HrController extends Controller
{
    public function documents(Request $request)
    {
        $query = EmployeeDocument::with('employee.department', 'employee.designation');

        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('verified')) {
            $query->where('verified', $request->verified === 'yes');
        }
        if ($request->filled('search')) {
            $query->where('document_name', 'like', "%{$request->search}%");
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(20);
        $documentTypes = EmployeeDocument::select('document_type')->distinct()->pluck('document_type');
        $employees = Employee::active()->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'employee_no']);

        return view('hr.documents', compact('documents', 'documentTypes', 'employees'));
    }

    public function evaluations(Request $request)
    {
        $query = EmployeeEvaluation::with('employee.department', 'employee.designation', 'evaluator');

        if ($request->filled('evaluation_type')) {
            $query->where('evaluation_type', $request->evaluation_type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('evaluation_type', 'like', "%{$request->search}%")
                  ->orWhere('comments', 'like', "%{$request->search}%");
            });
        }

        $evaluations = $query->orderBy('evaluation_date', 'desc')->paginate(20);
        $evaluationTypes = EmployeeEvaluation::select('evaluation_type')->distinct()->pluck('evaluation_type');
        $employees = Employee::active()->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'employee_no']);

        return view('hr.evaluations', compact('evaluations', 'evaluationTypes', 'employees'));
    }

    public function transfers(Request $request)
    {
        $query = EmployeeTransfer::with('employee.department', 'employee.designation', 'fromDepartment', 'toDepartment', 'fromDesignation', 'toDesignation');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('search')) {
            $query->where('reason', 'like', "%{$request->search}%");
        }

        $transfers = $query->orderBy('transfer_date', 'desc')->paginate(20);
        $employees = Employee::active()->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'employee_no']);
        $departments = Department::active()->orderBy('name')->get();

        return view('hr.transfers', compact('transfers', 'employees', 'departments'));
    }

    public function promotions(Request $request)
    {
        $query = EmployeePromotion::with('employee.department', 'employee.designation', 'fromDesignation', 'toDesignation', 'fromDepartment', 'toDepartment');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('search')) {
            $query->where('reason', 'like', "%{$request->search}%");
        }

        $promotions = $query->orderBy('promotion_date', 'desc')->paginate(20);
        $employees = Employee::active()->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'employee_no']);
        $designations = Designation::active()->orderBy('name')->get();

        return view('hr.promotions', compact('promotions', 'employees', 'designations'));
    }

    public function directory(Request $request)
    {
        $query = Employee::with('department', 'designation');

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('designation_id')) {
            $query->where('designation_id', $request->designation_id);
        }
        if ($request->filled('employment_type')) {
            $query->where('employment_type', $request->employment_type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                  ->orWhere('last_name', 'like', "%{$request->search}%")
                  ->orWhere('employee_no', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        $employees = $query->orderBy('first_name')->paginate(24);
        $departments = Department::active()->orderBy('name')->get();
        $designations = Designation::active()->orderBy('name')->get();

        return view('hr.directory', compact('employees', 'departments', 'designations'));
    }

    public function reports()
    {
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 'active')->count();

        $byDepartment = Employee::select('department_id', DB::raw('count(*) as total'))
            ->whereNotNull('department_id')
            ->groupBy('department_id')
            ->with('department')
            ->get();

        $byDesignation = Employee::select('designation_id', DB::raw('count(*) as total'))
            ->whereNotNull('designation_id')
            ->groupBy('designation_id')
            ->with('designation')
            ->get();

        $byEmploymentType = Employee::select('employment_type', DB::raw('count(*) as total'))
            ->whereNotNull('employment_type')
            ->groupBy('employment_type')
            ->get();

        $byGender = Employee::select('gender', DB::raw('count(*) as total'))
            ->whereNotNull('gender')
            ->groupBy('gender')
            ->get();

        $newHiresYear = Employee::whereYear('date_of_joining', now()->year)->count();
        $newHiresMonth = Employee::whereYear('date_of_joining', now()->year)
            ->whereMonth('date_of_joining', now()->month)
            ->count();

        $upcomingEvaluations = EmployeeEvaluation::with('employee')
            ->where('next_evaluation_date', '>=', now())
            ->where('next_evaluation_date', '<=', now()->addMonth())
            ->where('status', '!=', 'completed')
            ->count();

        $expiringDocuments = EmployeeDocument::with('employee')
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '>=', now())
            ->where('expiry_date', '<=', now()->addMonth())
            ->where('verified', false)
            ->count();

        return view('hr.reports', compact(
            'totalEmployees', 'activeEmployees',
            'byDepartment', 'byDesignation', 'byEmploymentType', 'byGender',
            'newHiresYear', 'newHiresMonth',
            'upcomingEvaluations', 'expiringDocuments'
        ));
    }
}
