<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FrontOffice\Appointment;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $appointments = Appointment::with('visitor')->when($request->date, fn($q) => $q->whereDate('appointment_date', $request->date))->when($request->date_from, fn($q) => $q->whereDate('appointment_date', '>=', $request->date_from))->when($request->date_to, fn($q) => $q->whereDate('appointment_date', '<=', $request->date_to))->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->person_to_meet, fn($q) => $q->where('person_to_meet', 'like', "%{$request->person_to_meet}%"))->orderBy('appointment_date', 'desc')->orderBy('appointment_time', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($appointments, 'Appointments retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'visitor_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'purpose' => 'required|string|max:500',
            'person_to_meet' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:500',
        ]);

        $appointment = Appointment::create($validated);
        return $this->createdResponse($appointment, 'Appointment created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(Appointment::findOrFail($id), 'Appointment retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $appointment = Appointment::findOrFail($id);
        $validated = $request->validate([
            'visitor_name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'email' => 'nullable|email|max:255',
            'purpose' => 'sometimes|string|max:500',
            'person_to_meet' => 'sometimes|string|max:255',
            'appointment_date' => 'sometimes|date|after_or_equal:today',
            'appointment_time' => 'sometimes|date_format:H:i',
            'notes' => 'nullable|string|max:500',
            'status' => 'nullable|string|in:scheduled,completed,cancelled,rescheduled',
        ]);
        $appointment->update($validated);
        return $this->updatedResponse($appointment->fresh(), 'Appointment updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Appointment::findOrFail($id)->delete();
        return $this->deletedResponse('Appointment deleted');
    }
}
