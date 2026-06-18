<?php

namespace App\Services\Events;

use App\Contracts\RepositoryInterface;
use App\Models\Events\Club;
use App\Repositories\Events\ClubRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class ClubService extends BaseService
{
    protected ClubRepository $clubRepository;

    public function __construct(ClubRepository $clubRepository)
    {
        parent::__construct();
        $this->clubRepository = $clubRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->clubRepository;
    }

    public function createClub(array $data): Club
    {
        return DB::transaction(function () use ($data) {
            $data['is_active'] = $data['is_active'] ?? true;
            $club = $this->clubRepository->create($data);
            $this->logActivity('club_created', $club);
            return $club;
        });
    }

    public function manageMembers(int $clubId, array $studentIds, string $action = 'add'): Club
    {
        return DB::transaction(function () use ($clubId, $studentIds, $action) {
            $club = $this->clubRepository->getClubWithMembers($clubId);

            if ($action === 'add') {
                $club->members()->syncWithoutDetaching($studentIds);
            } elseif ($action === 'remove') {
                $club->members()->detach($studentIds);
            }

            $this->logActivity('club_members_managed', [
                'club_id' => $clubId,
                'action' => $action,
                'student_ids' => $studentIds,
            ]);

            return $club->fresh();
        });
    }

    public function scheduleMeeting(int $clubId, array $meetingData): array
    {
        $club = $this->clubRepository->getClubWithMembers($clubId);

        $meeting = [
            'club_id' => $clubId,
            'title' => $meetingData['title'],
            'agenda' => $meetingData['agenda'] ?? null,
            'meeting_date' => $meetingData['meeting_date'],
            'start_time' => $meetingData['start_time'],
            'end_time' => $meetingData['end_time'],
            'venue' => $meetingData['venue'] ?? null,
            'organized_by' => auth()->id(),
        ];

        activity()->causedBy(auth()->user())->performedOn($club)->withProperties($meeting)->event('club_meeting_scheduled')->log("ClubService: Meeting scheduled for club {$clubId}");

        return $meeting;
    }
}
