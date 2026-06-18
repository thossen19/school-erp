<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Health\VaccinationRecord;
use App\Models\Student\StudentMedicalRecord;
use App\Services\Student\StudentMedicalService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentMedicalController extends Controller
{
    use ApiResponseTrait;

    protected StudentMedicalService $medicalService;

    public function __construct(StudentMedicalService $medicalService)
    {
        $this->medicalService = $medicalService;
    }

    public function getRecord(int $studentId): JsonResponse
    {
        $record = StudentMedicalRecord::where('student_id', $studentId)->first();
        if (!$record) {
            return $this->notFoundResponse('No medical record found for this student');
        }
        return $this->successResponse($record->load('vaccinations'), 'Medical record retrieved');
    }

    public function updateRecord(Request $request, int $studentId): JsonResponse
    {
        $validated = $request->validate([
            'blood_group' => 'nullable|string|max:10',
            'allergies' => 'nullable|string|max:2000',
            'chronic_conditions' => 'nullable|string|max:2000',
            'medications' => 'nullable|string|max:2000',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:100',
            'insurance_provider' => 'nullable|string|max:255',
            'insurance_policy_no' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:2000',
        ]);

        $record = StudentMedicalRecord::updateOrCreate(
            ['student_id' => $studentId],
            $validated
        );

        return $this->successResponse($record, 'Medical record updated');
    }

    public function addVaccination(Request $request, int $studentId): JsonResponse
    {
        $request->validate([
            'vaccine_name' => 'required|string|max:255',
            'date_administered' => 'required|date',
            'administered_by' => 'nullable|string|max:255',
            'dosage' => 'nullable|string|max:100',
            'batch_no' => 'nullable|string|max:100',
            'next_due_date' => 'nullable|date|after:date_administered',
            'notes' => 'nullable|string|max:500',
        ]);

        $vaccination = VaccinationRecord::create(array_merge($request->validated(), [
            'student_id' => $studentId,
            'administered_by' => $request->administered_by ?? $request->user()->name,
        ]));

        return $this->createdResponse($vaccination, 'Vaccination record added');
    }

    public function getHealthReport(int $studentId): JsonResponse
    {
        $report = $this->medicalService->generateHealthReport($studentId);
        return $this->successResponse($report, 'Health report generated');
    }
}
