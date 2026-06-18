<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Health\VaccinationRecord;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VaccinationController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $records = VaccinationRecord::with('student:id,first_name,last_name')->when($request->student_id, fn($q) => $q->where('student_id', $request->student_id))->when($request->vaccine_name, fn($q) => $q->where('vaccine_name', 'like', "%{$request->vaccine_name}%"))->when($request->date_from, fn($q) => $q->whereDate('date_administered', '>=', $request->date_from))->when($request->date_to, fn($q) => $q->whereDate('date_administered', '<=', $request->date_to))->orderBy('date_administered', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($records, 'Vaccination records retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'vaccine_name' => 'required|string|max:255',
            'date_administered' => 'required|date',
            'administered_by' => 'nullable|string|max:255',
            'dosage' => 'nullable|string|max:100',
            'batch_no' => 'nullable|string|max:100',
            'next_due_date' => 'nullable|date|after:date_administered',
            'notes' => 'nullable|string|max:500',
        ]);

        $record = VaccinationRecord::create($validated);
        return $this->createdResponse($record->load('student:id,first_name,last_name'), 'Vaccination record created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(VaccinationRecord::with('student:id,first_name,last_name')->findOrFail($id), 'Vaccination record retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $record = VaccinationRecord::findOrFail($id);
        $validated = $request->validate([
            'vaccine_name' => 'sometimes|string|max:255',
            'date_administered' => 'sometimes|date',
            'dosage' => 'nullable|string|max:100',
            'batch_no' => 'nullable|string|max:100',
            'next_due_date' => 'nullable|date|after:date_administered',
            'notes' => 'nullable|string|max:500',
        ]);
        $record->update($validated);
        return $this->updatedResponse($record->fresh(), 'Vaccination record updated');
    }

    public function destroy(int $id): JsonResponse
    {
        VaccinationRecord::findOrFail($id)->delete();
        return $this->deletedResponse('Vaccination record deleted');
    }
}
