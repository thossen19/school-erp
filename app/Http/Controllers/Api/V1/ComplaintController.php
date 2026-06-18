<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FrontOffice\Complaint;
use App\Services\FrontOffice\ComplaintService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    use ApiResponseTrait;

    protected ComplaintService $complaintService;

    public function __construct(ComplaintService $complaintService)
    {
        $this->complaintService = $complaintService;
    }

    public function index(Request $request): JsonResponse
    {
        $complaints = Complaint::with('assignedTo')->when($request->type, fn($q) => $q->where('type', $request->type))->when($request->priority, fn($q) => $q->where('priority', $request->priority))->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))->when($request->date_to, fn($q) => $q->whereDate('created_at', '<=', $request->date_to))->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%")->orWhere('complainant_name', 'like', "%{$request->search}%"))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($complaints, 'Complaints retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'complainant_name' => 'required|string|max:255',
            'complainant_phone' => 'nullable|string|max:20',
            'complainant_email' => 'nullable|email|max:255',
            'type' => 'required|string|in:academic,administrative,infrastructure,harassment,other',
            'priority' => 'required|string|in:low,medium,high,urgent',
            'description' => 'required|string|max:5000',
            'location' => 'nullable|string|max:255',
        ]);

        $complaint = Complaint::create($validated);
        return $this->createdResponse($complaint, 'Complaint registered');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            Complaint::with('assignedTo', 'resolutionBy')->findOrFail($id),
            'Complaint retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $complaint = Complaint::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:5000',
            'priority' => 'sometimes|string|in:low,medium,high,urgent',
        ]);
        $complaint->update($validated);
        return $this->updatedResponse($complaint->fresh(), 'Complaint updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Complaint::findOrFail($id)->delete();
        return $this->deletedResponse('Complaint deleted');
    }

    public function assign(Request $request, int $id): JsonResponse
    {
        $request->validate(['assigned_to' => 'required|integer|exists:users,id', 'remarks' => 'nullable|string|max:500']);
        $complaint = Complaint::findOrFail($id);
        $complaint->update(['assigned_to' => $request->assigned_to, 'status' => 'in_progress', 'assigned_at' => now()]);
        return $this->successResponse($complaint->fresh()->load('assignedTo'), 'Complaint assigned');
    }

    public function resolve(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'resolution_notes' => 'required|string|max:5000',
            'resolved_by' => 'required|integer|exists:users,id',
        ]);
        $complaint = Complaint::findOrFail($id);
        $complaint->update([
            'status' => 'resolved',
            'resolution_notes' => $request->resolution_notes,
            'resolved_by' => $request->resolved_by,
            'resolved_at' => now(),
        ]);
        return $this->successResponse($complaint->fresh(), 'Complaint resolved');
    }
}
