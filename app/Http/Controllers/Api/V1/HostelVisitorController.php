<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Hostel\HostelVisitor;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HostelVisitorController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $visitors = HostelVisitor::with('hostel', 'student')->when($request->hostel_id, fn($q) => $q->where('hostel_id', $request->hostel_id))->when($request->date, fn($q) => $q->whereDate('visit_date', $request->date))->when($request->status, fn($q) => $q->where('status', $request->status))->orderBy('visit_date', 'desc')->orderBy('in_time', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($visitors, 'Hostel visitors retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'hostel_id' => 'required|integer|exists:hostels,id',
            'student_id' => 'required|integer|exists:students,id',
            'visitor_name' => 'required|string|max:255',
            'visitor_phone' => 'required|string|max:20',
            'relation' => 'nullable|string|max:100',
            'visit_date' => 'required|date',
            'in_time' => 'required|date_format:H:i',
            'out_time' => 'nullable|date_format:H:i|after_or_equal:in_time',
            'purpose' => 'nullable|string|max:500',
            'id_proof' => 'nullable|string|max:100',
            'id_proof_no' => 'nullable|string|max:100',
            'status' => 'nullable|string|in:checked_in,checked_out',
        ]);

        $visitor = HostelVisitor::create($validated);
        return $this->createdResponse($visitor->load('hostel', 'student'), 'Visitor recorded');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(HostelVisitor::with('hostel', 'student')->findOrFail($id), 'Visitor record retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $visitor = HostelVisitor::findOrFail($id);
        $validated = $request->validate([
            'visitor_name' => 'sometimes|string|max:255',
            'visitor_phone' => 'sometimes|string|max:20',
            'purpose' => 'nullable|string|max:500',
            'out_time' => 'nullable|date_format:H:i',
            'status' => 'nullable|string|in:checked_in,checked_out',
        ]);
        $visitor->update($validated);
        return $this->updatedResponse($visitor->fresh()->load('hostel'), 'Visitor record updated');
    }

    public function destroy(int $id): JsonResponse
    {
        HostelVisitor::findOrFail($id)->delete();
        return $this->deletedResponse('Visitor record deleted');
    }
}
