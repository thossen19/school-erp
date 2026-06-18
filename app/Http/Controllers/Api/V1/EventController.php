<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Events\StoreEventRequest;
use App\Models\Events\Event;
use App\Models\Events\EventRegistration;
use App\Services\Events\EventService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    use ApiResponseTrait;

    protected EventService $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function index(Request $request): JsonResponse
    {
        $events = Event::withCount('registrations')->when($request->type, fn($q) => $q->where('event_type', $request->type))->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->upcoming, fn($q) => $q->where('start_date', '>=', now()))->when($request->date_from, fn($q) => $q->whereDate('start_date', '>=', $request->date_from))->when($request->date_to, fn($q) => $q->whereDate('end_date', '<=', $request->date_to))->orderBy('start_date', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($events, 'Events retrieved');
    }

    public function store(StoreEventRequest $request): JsonResponse
    {
        $event = Event::create($request->validated());

        if ($request->hasFile('image')) {
            $event->update(['image' => $request->file('image')->store('events', 'public')]);
        }

        return $this->createdResponse($event, 'Event created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            Event::with('registrations.user')->withCount('registrations')->findOrFail($id),
            'Event retrieved'
        );
    }

    public function update(StoreEventRequest $request, int $id): JsonResponse
    {
        $event = Event::findOrFail($id);
        $event->update($request->validated());

        if ($request->hasFile('image')) {
            $event->update(['image' => $request->file('image')->store('events', 'public')]);
        }

        return $this->updatedResponse($event->fresh(), 'Event updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Event::findOrFail($id)->delete();
        return $this->deletedResponse('Event deleted');
    }

    public function manageRegistration(Request $request, int $eventId): JsonResponse
    {
        $request->validate(['action' => 'required|string|in:register,unregister']);

        if ($request->action === 'register') {
            $registration = EventRegistration::firstOrCreate([
                'event_id' => $eventId,
                'user_id' => $request->user()->id,
            ]);
            return $this->createdResponse($registration, 'Registered for event');
        }

        EventRegistration::where('event_id', $eventId)->where('user_id', $request->user()->id)->delete();
        return $this->successResponse(null, 'Unregistered from event');
    }

    public function getCalendar(Request $request): JsonResponse
    {
        $request->validate(['year' => 'required|integer|min:2020|max:2099', 'month' => 'nullable|integer|min:1|max:12']);

        $events = Event::whereYear('start_date', $request->year)->when($request->month, fn($q) => $q->whereMonth('start_date', $request->month))->where('status', 'published')->orderBy('start_date')->get(['id', 'title', 'event_type', 'start_date', 'end_date', 'start_time', 'end_time', 'venue', 'color']);

        return $this->successResponse($events, 'Event calendar');
    }
}
