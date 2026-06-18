<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FrontOffice\CallLog;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CallLogController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $calls = CallLog::when($request->type, fn($q) => $q->where('call_type', $request->type))->when($request->date, fn($q) => $q->whereDate('call_date', $request->date))->when($request->date_from, fn($q) => $q->whereDate('call_date', '>=', $request->date_from))->when($request->date_to, fn($q) => $q->whereDate('call_date', '<=', $request->date_to))->when($request->search, fn($q) => $q->where('caller_name', 'like', "%{$request->search}%")->orWhere('phone', 'like', "%{$request->search}%"))->orderBy('call_date', 'desc')->orderBy('call_time', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($calls, 'Call logs retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'caller_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'call_type' => 'required|string|in:incoming,outgoing',
            'purpose' => 'required|string|max:500',
            'description' => 'nullable|string|max:2000',
            'call_date' => 'required|date',
            'call_time' => 'nullable|date_format:H:i',
            'duration_minutes' => 'nullable|integer|min:0',
            'follow_up_date' => 'nullable|date',
            'assigned_to' => 'nullable|integer|exists:users,id',
            'status' => 'nullable|string|in:completed,pending,follow_up,missed',
        ]);

        $call = CallLog::create($validated);
        return $this->createdResponse($call, 'Call log created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(CallLog::with('assignedTo')->findOrFail($id), 'Call log retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $call = CallLog::findOrFail($id);
        $validated = $request->validate([
            'purpose' => 'sometimes|string|max:500',
            'description' => 'nullable|string|max:2000',
            'follow_up_date' => 'nullable|date',
            'status' => 'nullable|string|in:completed,pending,follow_up,missed',
        ]);
        $call->update($validated);
        return $this->updatedResponse($call->fresh(), 'Call log updated');
    }

    public function destroy(int $id): JsonResponse
    {
        CallLog::findOrFail($id)->delete();
        return $this->deletedResponse('Call log deleted');
    }
}
