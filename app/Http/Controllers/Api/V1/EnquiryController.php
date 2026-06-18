<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FrontOffice\Enquiry;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $enquiries = Enquiry::when($request->type, fn($q) => $q->where('type', $request->type))->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->source, fn($q) => $q->where('source', $request->source))->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))->when($request->date_to, fn($q) => $q->whereDate('created_at', '<=', $request->date_to))->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")->orWhere('phone', 'like', "%{$request->search}%")->orWhere('email', 'like', "%{$request->search}%"))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($enquiries, 'Enquiries retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'type' => 'required|string|in:admission,general,complaint,suggestion,other',
            'source' => 'nullable|string|max:100',
            'description' => 'required|string|max:5000',
            'notes' => 'nullable|string|max:1000',
            'follow_up_date' => 'nullable|date',
            'assigned_to' => 'nullable|integer|exists:users,id',
        ]);

        $enquiry = Enquiry::create($validated);
        return $this->createdResponse($enquiry, 'Enquiry created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(Enquiry::with('assignedTo')->findOrFail($id), 'Enquiry retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $enquiry = Enquiry::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'email' => 'nullable|email|max:255',
            'type' => 'sometimes|string|in:admission,general,complaint,suggestion,other',
            'description' => 'sometimes|string|max:5000',
            'notes' => 'nullable|string|max:1000',
            'follow_up_date' => 'nullable|date',
            'status' => 'nullable|string|in:open,in_progress,closed',
            'assigned_to' => 'nullable|integer|exists:users,id',
        ]);
        $enquiry->update($validated);
        return $this->updatedResponse($enquiry->fresh(), 'Enquiry updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Enquiry::findOrFail($id)->delete();
        return $this->deletedResponse('Enquiry deleted');
    }
}
