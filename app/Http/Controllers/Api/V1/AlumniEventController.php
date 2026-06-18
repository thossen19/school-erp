<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Alumni\AlumniEvent;
use App\Models\Alumni\AlumniEventAttendee;
use App\Services\Alumni\AlumniEventService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlumniEventController extends Controller
{
    use ApiResponseTrait;

    protected AlumniEventService $eventService;

    public function __construct(AlumniEventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function index(Request $request): JsonResponse
    {
        $events = AlumniEvent::withCount('attendees')->when($request->type, fn($q) => $q->where('event_type', $request->type))->when($request->upcoming, fn($q) => $q->where('event_date', '>=', now()))->when($request->status, fn($q) => $q->where('status', $request->status))->orderBy('event_date', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($events, 'Alumni events retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'event_type' => 'required|string|in:reunion,networking,workshop,seminar,social,fundraiser,other',
            'event_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after_or_equal:start_time',
            'venue' => 'nullable|string|max:255',
            'max_attendees' => 'nullable|integer|min:0',
            'registration_fee' => 'nullable|numeric|min:0',
            'organizer' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'status' => 'nullable|string|in:planned,confirmed,ongoing,completed,cancelled',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $event = AlumniEvent::create($validated);

        if ($request->hasFile('image')) {
            $event->update(['image' => $request->file('image')->store('alumni/events', 'public')]);
        }

        return $this->createdResponse($event, 'Alumni event created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            AlumniEvent::with('attendees.alumnus')->withCount('attendees')->findOrFail($id),
            'Alumni event retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $event = AlumniEvent::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:5000',
            'event_type' => 'sometimes|string|in:reunion,networking,workshop,seminar,social,fundraiser,other',
            'event_date' => 'sometimes|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'venue' => 'nullable|string|max:255',
            'max_attendees' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:planned,confirmed,ongoing,completed,cancelled',
        ]);
        $event->update($validated);
        return $this->updatedResponse($event->fresh(), 'Alumni event updated');
    }

    public function destroy(int $id): JsonResponse
    {
        AlumniEvent::findOrFail($id)->delete();
        return $this->deletedResponse('Alumni event deleted');
    }

    public function sendInvitation(Request $request, int $eventId): JsonResponse
    {
        $request->validate([
            'alumni_ids' => 'required|array|min:1',
            'alumni_ids.*' => 'integer|exists:alumni,id',
        ]);

        $result = $this->eventService->sendInvitations($eventId, $request->alumni_ids);
        return $this->successResponse($result, 'Invitations sent');
    }

    public function trackAttendance(Request $request, int $eventId): JsonResponse
    {
        $request->validate([
            'alumni_id' => 'required|integer|exists:alumni,id',
            'attended' => 'required|boolean',
        ]);

        $attendee = AlumniEventAttendee::updateOrCreate(
            ['alumni_event_id' => $eventId, 'alumni_id' => $request->alumni_id],
            ['attended' => $request->attended, 'attended_at' => $request->attended ? now() : null]
        );

        return $this->successResponse($attendee, 'Attendance tracked');
    }
}
