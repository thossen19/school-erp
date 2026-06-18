<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Health\HealthRecord;
use App\Services\Health\HealthService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HealthRecordController extends Controller
{
    use ApiResponseTrait;

    protected HealthService $healthService;

    public function __construct(HealthService $healthService)
    {
        $this->healthService = $healthService;
    }

    public function index(Request $request): JsonResponse
    {
        $records = HealthRecord::with('student:id,first_name,last_name,admission_no')->when($request->student_id, fn($q) => $q->where('student_id', $request->student_id))->when($request->date_from, fn($q) => $q->whereDate('record_date', '>=', $request->date_from))->when($request->date_to, fn($q) => $q->whereDate('record_date', '<=', $request->date_to))->orderBy('record_date', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($records, 'Health records retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'record_date' => 'required|date',
            'height_cm' => 'nullable|numeric|min:0|max:300',
            'weight_kg' => 'nullable|numeric|min:0|max:500',
            'bmi' => 'nullable|numeric|min:0|max:100',
            'blood_pressure' => 'nullable|string|max:20',
            'heart_rate' => 'nullable|integer|min:0|max:300',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'vision_left' => 'nullable|string|max:20',
            'vision_right' => 'nullable|string|max:20',
            'allergies' => 'nullable|string|max:1000',
            'medical_conditions' => 'nullable|string|max:1000',
            'medications' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:2000',
            'examined_by' => 'nullable|string|max:255',
        ]);

        $record = HealthRecord::create($validated);
        return $this->createdResponse($record->load('student:id,first_name,last_name'), 'Health record created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(HealthRecord::with('student:id,first_name,last_name')->findOrFail($id), 'Health record retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $record = HealthRecord::findOrFail($id);
        $validated = $request->validate([
            'height_cm' => 'nullable|numeric|min:0|max:300',
            'weight_kg' => 'nullable|numeric|min:0|max:500',
            'blood_pressure' => 'nullable|string|max:20',
            'heart_rate' => 'nullable|integer|min:0|max:300',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'allergies' => 'nullable|string|max:1000',
            'medical_conditions' => 'nullable|string|max:1000',
            'medications' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:2000',
        ]);
        $record->update($validated);
        return $this->updatedResponse($record->fresh(), 'Health record updated');
    }

    public function destroy(int $id): JsonResponse
    {
        HealthRecord::findOrFail($id)->delete();
        return $this->deletedResponse('Health record deleted');
    }

    public function getReport(int $studentId): JsonResponse
    {
        $report = $this->healthService->generateStudentHealthReport($studentId);
        return $this->successResponse($report, 'Health report generated');
    }
}
