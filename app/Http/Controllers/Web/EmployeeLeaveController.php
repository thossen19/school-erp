<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Attendance\Holiday;
use App\Models\Attendance\LeaveApprovalWorkflow;
use App\Models\Attendance\LeaveBalance;
use App\Models\Attendance\LeaveEncashment;
use App\Models\Attendance\LeavePolicy;
use App\Models\Attendance\LeaveRequest;
use App\Models\Attendance\LeaveType;
use App\Models\Hr\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeLeaveController extends Controller
{
    public function policies(Request $request)
    {
        $query = LeavePolicy::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
            });
        }

        $policies = $query->orderBy('name')->paginate(20);
        return view('employee-leave.policies', compact('policies'));
    }

    public function types(Request $request)
    {
        $query = LeaveType::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
            });
        }

        $types = $query->orderBy('name')->paginate(20);
        return view('employee-leave.types', compact('types'));
    }

    public function requests(Request $request)
    {
        $query = LeaveRequest::with('user', 'leaveType');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->leave_type_id);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('end_date', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $query->where('reason', 'like', "%{$request->search}%");
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(20);
        $leaveTypes = LeaveType::active()->get();
        $users = \App\Models\User::orderBy('name')->get(['id', 'name', 'email']);

        return view('employee-leave.requests', compact('requests', 'leaveTypes', 'users'));
    }

    public function approvalWorkflows(Request $request)
    {
        $query = LeaveApprovalWorkflow::with('leaveType');

        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->leave_type_id);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $workflows = $query->orderBy('approval_level')->paginate(20);
        $leaveTypes = LeaveType::active()->get();

        return view('employee-leave.approval-workflows', compact('workflows', 'leaveTypes'));
    }

    public function balances(Request $request)
    {
        $query = LeaveBalance::with('leaveType', 'user');

        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->leave_type_id);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $balances = $query->orderBy('remaining_days')->paginate(20);
        $leaveTypes = LeaveType::active()->get();
        $users = \App\Models\User::orderBy('name')->get(['id', 'name', 'email']);

        return view('employee-leave.balances', compact('balances', 'leaveTypes', 'users'));
    }

    public function encashments(Request $request)
    {
        $query = LeaveEncashment::with('employee', 'leaveType');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('search')) {
            $query->where('remarks', 'like', "%{$request->search}%");
        }

        $encashments = $query->orderBy('encashment_date', 'desc')->paginate(20);
        $employees = Employee::active()->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'employee_no']);

        return view('employee-leave.encashments', compact('encashments', 'employees'));
    }

    public function holidayCalendar(Request $request)
    {
        $query = Holiday::query();

        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $holidays = $query->orderBy('date')->paginate(20);
        $years = Holiday::select(DB::raw('YEAR(date) as year'))->distinct()->orderBy('year', 'desc')->pluck('year');

        return view('employee-leave.holiday-calendar', compact('holidays', 'years'));
    }

    public function storeHoliday(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:public,religious,school,exam,other',
            'description' => 'nullable|string',
            'is_recurring_annually' => 'boolean',
            'status' => 'boolean',
        ]);

        $validated['school_id'] = session('school_id', 1);
        $validated['is_recurring_annually'] = $request->boolean('is_recurring_annually');
        $validated['status'] = $request->boolean('status', true);

        Holiday::create($validated);

        return redirect()->route('hr.employee-leave.holiday-calendar')
            ->with('success', 'Holiday created successfully.');
    }

    public function updateHoliday(Request $request, $id)
    {
        $holiday = Holiday::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:public,religious,school,exam,other',
            'description' => 'nullable|string',
            'is_recurring_annually' => 'boolean',
            'status' => 'boolean',
        ]);

        $validated['is_recurring_annually'] = $request->boolean('is_recurring_annually');
        $validated['status'] = $request->boolean('status', true);

        $holiday->update($validated);

        return redirect()->route('hr.employee-leave.holiday-calendar')
            ->with('success', 'Holiday updated successfully.');
    }

    public function destroyHoliday($id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();

        return redirect()->route('hr.employee-leave.holiday-calendar')
            ->with('success', 'Holiday deleted successfully.');
    }

    public function reports()
    {
        $totalRequests = LeaveRequest::count();
        $pendingRequests = LeaveRequest::where('status', 'pending')->count();
        $approvedThisMonth = LeaveRequest::where('status', 'approved')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $rejectedThisMonth = LeaveRequest::where('status', 'rejected')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $byStatus = LeaveRequest::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')->get();

        $byType = LeaveRequest::select('leave_type_id', DB::raw('count(*) as total'))
            ->whereNotNull('leave_type_id')
            ->groupBy('leave_type_id')
            ->with('leaveType')
            ->get();

        $byMonth = LeaveRequest::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('count(*) as total')
        )
            ->whereYear('created_at', now()->year)
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();

        $totalLeaveTypes = LeaveType::count();
        $activePolicies = LeavePolicy::where('is_active', true)->count();
        $lowBalanceEmployees = LeaveBalance::where('remaining_days', '<=', 2)->count();
        $pendingEncashments = LeaveEncashment::where('status', 'pending')->count();
        $upcomingHolidays = Holiday::where('date', '>=', now())
            ->where('status', true)
            ->orderBy('date')
            ->take(5)
            ->get();

        return view('employee-leave.reports', compact(
            'totalRequests', 'pendingRequests', 'approvedThisMonth', 'rejectedThisMonth',
            'byStatus', 'byType', 'byMonth',
            'totalLeaveTypes', 'activePolicies', 'lowBalanceEmployees', 'pendingEncashments',
            'upcomingHolidays'
        ));
    }
}
