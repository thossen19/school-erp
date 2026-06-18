<?php

namespace App\Services\Events;

use App\Contracts\RepositoryInterface;
use App\Models\Events\Event;
use App\Repositories\Events\EventRepository;
use App\Repositories\Events\EventRegistrationRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class EventService extends BaseService
{
    protected EventRepository $eventRepository;
    protected EventRegistrationRepository $registrationRepository;

    public function __construct(
        EventRepository $eventRepository,
        EventRegistrationRepository $registrationRepository
    ) {
        parent::__construct();
        $this->eventRepository = $eventRepository;
        $this->registrationRepository = $registrationRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->eventRepository;
    }

    public function createEvent(array $data): Event
    {
        return DB::transaction(function () use ($data) {
            $data['status'] = $data['status'] ?? 'draft';
            $event = $this->eventRepository->create($data);
            $this->logActivity('event_created', $event);
            return $event;
        });
    }

    public function manageRegistration(int $eventId, int $studentId, string $action = 'register'): \App\Models\Events\EventRegistration
    {
        return DB::transaction(function () use ($eventId, $studentId, $action) {
            $event = $this->eventRepository->getById($eventId);

            if ($action === 'register') {
                if ($this->registrationRepository->checkRegistration($eventId, $studentId)) {
                    throw new \App\Exceptions\ServiceException("Student is already registered for this event.");
                }

                $registration = $this->registrationRepository->create([
                    'event_id' => $eventId,
                    'student_id' => $studentId,
                    'status' => 'confirmed',
                    'registered_at' => now(),
                ]);
            } else {
                $registrations = $this->registrationRepository->findByEvent($eventId);
                $registration = $registrations->firstWhere('student_id', $studentId);

                if (!$registration) {
                    throw new \App\Exceptions\ServiceException("Registration not found.");
                }

                $this->registrationRepository->cancelRegistration($registration->id);
                $registration = $registration->fresh();
            }

            $this->logActivity('event_registration_' . $action, $registration);

            return $registration;
        });
    }

    public function sendEventNotification(int $eventId, string $message): bool
    {
        try {
            $event = $this->eventRepository->getById($eventId);
            $registrations = $this->registrationRepository->getConfirmedRegistrations($eventId);

            activity()->causedBy(auth()->user())->performedOn($event)->withProperties([
                    'message' => $message,
                    'recipients' => $registrations->count(),
                    'sent_at' => now(),
                ])->event('event_notification')->log("EventNotification: Notification sent for event {$eventId} to {$registrations->count()} registrants");

            return true;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('EventService@sendEventNotification: ' . $e->getMessage());
            return false;
        }
    }

    public function getEventReport(int $eventId): array
    {
        $event = $this->eventRepository->getById($eventId);
        $confirmed = $this->registrationRepository->getConfirmedRegistrations($eventId);
        $pending = $this->registrationRepository->getPendingRegistrations($eventId);

        $report = [
            'event' => $event->toArray(),
            'total_registrations' => $confirmed->count() + $pending->count(),
            'confirmed' => $confirmed->count(),
            'pending' => $pending->count(),
            'attended' => $confirmed->where('attended', true)->count(),
        ];

        $this->logActivity('event_report_viewed', $report);

        return $report;
    }
}
