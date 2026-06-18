<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Attendance\LeaveRequest;
use App\Models\Attendance\LeaveType;
use App\Models\Hr\Employee;
use App\Services\Attendance\LeaveService;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    protected LeaveService $leaveService;

    public function __construct(LeaveService $leaveService)
    {
        $this->leaveService = $leaveService;
    }

    public function index(Request $request)
    {
        $leaves = LeaveRequest::with('user', 'leaveType')->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))->when($request->date_from, fn($q) => $q->whereDate('start_date', '>=', $request->date_from))->when($request->date_to, fn($q) => $q->whereDate('end_date', '<=', $request->date_to))->orderBy('created_at', 'desc')->paginate(20);

        $leaveTypes = LeaveType::active()->get();
        return view('leaves.index', compact('leaves', 'leaveTypes'));
    }

    public function create()
    {
        $leaveTypes = LeaveType::active()->get();
        return view('leaves.create', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_type_id' => 'required|integer|exists:leave_types,id',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
            'is_half_day' => 'boolean',
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['status'] = 'pending';
        LeaveRequest::create($validated);
        return redirect()->route('leaves.index')->with('success', 'Leave request submitted');
    }

    public function show(int $id)
    {
        $leave = LeaveRequest::with('user', 'leaveType', 'approver')->findOrFail($id);
        return view('leaves.show', compact('leave'));
    }

    public function approve(Request $request, int $id)
    {
        $leave = LeaveRequest::findOrFail($id);
        $leave->update(['status' => 'approved', 'approved_by' => $request->user()->id, 'approved_at' => now()]);
        return redirect()->route('leaves.index')->with('success', 'Leave approved');
    }

    public function reject(Request $request, int $id)
    {
        $request->validate(['rejection_reason' => 'required|string|max:500']);
        $leave = LeaveRequest::findOrFail($id);
        $leave->update(['status' => 'rejected', 'rejection_reason' => $request->rejection_reason, 'rejected_at' => now()]);
        return redirect()->route('leaves.index')->with('success', 'Leave rejected');
    }
}
