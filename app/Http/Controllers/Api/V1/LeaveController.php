<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Attendance\LeaveBalance;
use App\Models\Attendance\LeaveRequest;
use App\Services\Attendance\LeaveService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    use ApiResponseTrait;

    protected LeaveService $leaveService;

    public function __construct(LeaveService $leaveService)
    {
        $this->leaveService = $leaveService;
    }

    public function index(Request $request): JsonResponse
    {
        $leaves = LeaveRequest::with('user', 'leaveType')->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))->when($request->leave_type_id, fn($q) => $q->where('leave_type_id', $request->leave_type_id))->when($request->date_from, fn($q) => $q->whereDate('start_date', '>=', $request->date_from))->when($request->date_to, fn($q) => $q->whereDate('end_date', '<=', $request->date_to))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($leaves, 'Leave requests retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'leave_type_id' => 'required|integer|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
            'handover_notes' => 'nullable|string|max:500',
            'contact_during_leave' => 'nullable|string|max:100',
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $leave = LeaveRequest::create(array_merge($validated, [
            'user_id' => $request->user()->id,
            'status' => 'pending',
            'applied_on' => now(),
        ]));

        return $this->createdResponse($leave->load('leaveType', 'user'), 'Leave request submitted');
    }

    public function approve(Request $request, int $id): JsonResponse
    {
        $leave = LeaveRequest::findOrFail($id);
        $leave->update(['status' => 'approved', 'approved_by' => $request->user()->id, 'approved_at' => now()]);
        $this->leaveService->deductLeaveBalance($leave);
        return $this->successResponse($leave->load('leaveType'), 'Leave approved');
    }

    public function reject(Request $request, int $id): JsonResponse
    {
        $request->validate(['rejection_reason' => 'nullable|string|max:500']);
        $leave = LeaveRequest::findOrFail($id);
        $leave->update(['status' => 'rejected', 'rejection_reason' => $request->rejection_reason, 'approved_by' => $request->user()->id]);
        return $this->successResponse($leave, 'Leave rejected');
    }

    public function getBalance(Request $request): JsonResponse
    {
        $balance = LeaveBalance::where('user_id', $request->user()->id)->with('leaveType')->get();
        return $this->successResponse($balance, 'Leave balance retrieved');
    }

    public function getCalendar(Request $request): JsonResponse
    {
        $request->validate(['year' => 'required|integer|min:2020|max:2099']);
        $calendar = $this->leaveService->getLeaveCalendar($request->year);
        return $this->successResponse($calendar, 'Leave calendar retrieved');
    }
}
