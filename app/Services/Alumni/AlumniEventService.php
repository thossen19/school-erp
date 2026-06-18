<?php

namespace App\Services\Alumni;

use App\Contracts\RepositoryInterface;
use App\Models\Alumni\AlumniEvent;
use App\Repositories\Alumni\AlumniEventRepository;
use App\Repositories\Alumni\AlumniRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class AlumniEventService extends BaseService
{
    protected AlumniEventRepository $alumniEventRepository;
    protected AlumniRepository $alumniRepository;

    public function __construct(
        AlumniEventRepository $alumniEventRepository,
        AlumniRepository $alumniRepository
    ) {
        parent::__construct();
        $this->alumniEventRepository = $alumniEventRepository;
        $this->alumniRepository = $alumniRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->alumniEventRepository;
    }

    public function createEvent(array $data): AlumniEvent
    {
        return DB::transaction(function () use ($data) {
            $data['status'] = $data['status'] ?? 'draft';
            $event = $this->alumniEventRepository->create($data);

            $this->logActivity('alumni_event_created', $event);

            return $event;
        });
    }

    public function sendInvitation(int $eventId, ?array $alumniIds = null): bool
    {
        try {
            $event = $this->alumniEventRepository->getById($eventId);
            $recipients = $alumniIds
                ? collect($alumniIds)->map(fn($id) => $this->alumniRepository->getById($id))
                : $this->alumniRepository->getConnectedAlumni();

            activity()->causedBy(auth()->user())->performedOn($event)->withProperties([
                    'event_id' => $eventId,
                    'recipients_count' => $recipients->count(),
                    'sent_at' => now(),
                ])->event('invitation_sent')->log("AlumniEventService: Invitations sent for event {$eventId}");

            return true;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('AlumniEventService@sendInvitation: ' . $e->getMessage());
            return false;
        }
    }

    public function trackAttendance(int $eventId, array $alumniIds): array
    {
        return DB::transaction(function () use ($eventId, $alumniIds) {
            $event = $this->alumniEventRepository->getEventWithAttendees($eventId);

            $attendees = collect();
            foreach ($alumniIds as $alumniId) {
                $alumni = $this->alumniRepository->getById($alumniId);

                if ($event->attendees()->where('alumni_id', $alumniId)->doesntExist()) {
                    $event->attendees()->attach($alumniId, ['attended_at' => now()]);
                }

                $attendees->push($alumni);
            }

            $this->logActivity('alumni_event_attendance_tracked', [
                'event_id' => $eventId,
                'count' => $attendees->count(),
            ]);

            return ['event' => $event->fresh(), 'attendees' => $attendees];
        });
    }
}
