<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Fee\Scholarship;
use App\Services\Fee\ScholarshipService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    use ApiResponseTrait;

    protected ScholarshipService $scholarshipService;

    public function __construct(ScholarshipService $scholarshipService)
    {
        $this->scholarshipService = $scholarshipService;
    }

    public function index(Request $request): JsonResponse
    {
        $scholarships = Scholarship::withCount('students')->when($request->type, fn($q) => $q->where('type', $request->type))->when($request->is_active, fn($q) => $q->where('is_active', $request->boolean('is_active')))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($scholarships, 'Scholarships retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:scholarships,code',
            'type' => 'required|string|in:merit_based,need_based,sports,cultural,minority,other',
            'amount_type' => 'required|string|in:percentage,fixed',
            'amount' => 'required|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'criteria' => 'nullable|string|max:2000',
            'is_active' => 'boolean',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'available_seats' => 'nullable|integer|min:0',
        ]);

        $scholarship = Scholarship::create($validated);
        return $this->createdResponse($scholarship, 'Scholarship created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(Scholarship::with('students', 'students.student')->findOrFail($id), 'Scholarship retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $scholarship = Scholarship::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:50|unique:scholarships,code,' . $id,
            'type' => 'sometimes|string|in:merit_based,need_based,sports,cultural,minority,other',
            'amount_type' => 'sometimes|string|in:percentage,fixed',
            'amount' => 'sometimes|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'criteria' => 'nullable|string|max:2000',
            'is_active' => 'boolean',
            'valid_from' => 'sometimes|date',
            'valid_to' => 'sometimes|date|after_or_equal:valid_from',
            'available_seats' => 'nullable|integer|min:0',
        ]);
        $scholarship->update($validated);
        return $this->updatedResponse($scholarship->fresh(), 'Scholarship updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Scholarship::findOrFail($id)->delete();
        return $this->deletedResponse('Scholarship deleted');
    }

    public function award(Request $request, int $scholarshipId): JsonResponse
    {
        $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'academic_year_id' => 'required|integer|exists:academic_years,id',
            'award_amount' => 'required|numeric|min:0',
            'remarks' => 'nullable|string|max:500',
        ]);

        $result = $this->scholarshipService->awardScholarship($scholarshipId, $request->all());
        return $this->createdResponse($result, 'Scholarship awarded');
    }

    public function revoke(Request $request, int $scholarshipId, int $studentId): JsonResponse
    {
        $result = $this->scholarshipService->revokeScholarship($scholarshipId, $studentId);
        return $this->successResponse($result, 'Scholarship revoked');
    }
}
