<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Timetable\SubstitutionRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubstitutionController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $substitutions = SubstitutionRequest::with('originalTeacher', 'substituteTeacher', 'period')->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->date, fn($q) => $q->whereDate('substitution_date', $request->date))->when($request->teacher_id, fn($q) => $q->where('substitute_teacher_id', $request->teacher_id))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($substitutions, 'Substitution requests retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'timetable_period_id' => 'required|integer|exists:timetable_periods,id',
            'original_teacher_id' => 'required|integer|exists:employees,id',
            'substitute_teacher_id' => 'required|integer|exists:employees,id',
            'substitution_date' => 'required|date',
            'reason' => 'required|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        $substitution = SubstitutionRequest::create(array_merge($validated, ['status' => 'pending']));
        return $this->createdResponse($substitution->load('originalTeacher', 'substituteTeacher'), 'Substitution requested');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            SubstitutionRequest::with('originalTeacher', 'substituteTeacher', 'period.subject', 'period.timetable')->findOrFail($id),
            'Substitution request retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $substitution = SubstitutionRequest::findOrFail($id);
        $validated = $request->validate([
            'substitute_teacher_id' => 'sometimes|integer|exists:employees,id',
            'substitution_date' => 'sometimes|date',
            'reason' => 'sometimes|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);
        $substitution->update($validated);
        return $this->updatedResponse($substitution->fresh()->load('originalTeacher', 'substituteTeacher'), 'Substitution updated');
    }

    public function destroy(int $id): JsonResponse
    {
        SubstitutionRequest::findOrFail($id)->delete();
        return $this->deletedResponse('Substitution deleted');
    }

    public function approve(Request $request, int $id): JsonResponse
    {
        $substitution = SubstitutionRequest::findOrFail($id);
        $substitution->update(['status' => 'approved', 'approved_by' => $request->user()->id, 'approved_at' => now()]);
        return $this->successResponse($substitution, 'Substitution approved');
    }

    public function reject(Request $request, int $id): JsonResponse
    {
        $request->validate(['rejection_reason' => 'nullable|string|max:500']);
        $substitution = SubstitutionRequest::findOrFail($id);
        $substitution->update(['status' => 'rejected', 'rejection_reason' => $request->rejection_reason]);
        return $this->successResponse($substitution, 'Substitution rejected');
    }
}
