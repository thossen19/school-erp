<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FrontOffice\Visitor;
use App\Services\FrontOffice\VisitorService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    use ApiResponseTrait;

    protected VisitorService $visitorService;

    public function __construct(VisitorService $visitorService)
    {
        $this->visitorService = $visitorService;
    }

    public function index(Request $request): JsonResponse
    {
        $visitors = Visitor::when($request->purpose, fn($q) => $q->where('purpose', $request->purpose))->when($request->date, fn($q) => $q->whereDate('created_at', $request->date))->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))->when($request->date_to, fn($q) => $q->whereDate('created_at', '<=', $request->date_to))->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")->orWhere('phone', 'like', "%{$request->search}%"))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($visitors, 'Visitors retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'purpose' => 'required|string|max:500',
            'person_to_meet' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'id_proof' => 'nullable|string|max:100',
            'id_proof_no' => 'nullable|string|max:100',
            'vehicle_no' => 'nullable|string|max:50',
            'no_of_persons' => 'nullable|integer|min:1|max:50',
            'notes' => 'nullable|string|max:500',
        ]);

        $visitor = Visitor::create($validated);

        if ($request->hasFile('photo')) {
            $visitor->update(['photo' => $request->file('photo')->store('visitors', 'public')]);
        }

        return $this->createdResponse($visitor, 'Visitor recorded');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(Visitor::findOrFail($id), 'Visitor retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $visitor = Visitor::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'email' => 'nullable|email|max:255',
            'purpose' => 'sometimes|string|max:500',
            'person_to_meet' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);
        $visitor->update($validated);
        return $this->updatedResponse($visitor->fresh(), 'Visitor updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Visitor::findOrFail($id)->delete();
        return $this->deletedResponse('Visitor record deleted');
    }

    public function checkIn(Request $request, int $id): JsonResponse
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->update(['check_in' => now(), 'status' => 'checked_in']);
        return $this->successResponse($visitor, 'Visitor checked in');
    }

    public function checkOut(Request $request, int $id): JsonResponse
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->update(['check_out' => now(), 'status' => 'checked_out']);
        return $this->successResponse($visitor, 'Visitor checked out');
    }
}
