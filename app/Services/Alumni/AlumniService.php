<?php

namespace App\Services\Alumni;

use App\Contracts\RepositoryInterface;
use App\Models\Alumni\Alumni;
use App\Repositories\Alumni\AlumniRepository;
use App\Repositories\Alumni\AlumniEventRepository;
use App\Repositories\Alumni\AlumniDonationRepository;
use App\Repositories\Alumni\JobPostRepository;
use App\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AlumniService extends BaseService
{
    protected AlumniRepository $alumniRepository;
    protected AlumniEventRepository $alumniEventRepository;
    protected AlumniDonationRepository $donationRepository;
    protected JobPostRepository $jobPostRepository;

    public function __construct(
        AlumniRepository $alumniRepository,
        AlumniEventRepository $alumniEventRepository,
        AlumniDonationRepository $donationRepository,
        JobPostRepository $jobPostRepository
    ) {
        parent::__construct();
        $this->alumniRepository = $alumniRepository;
        $this->alumniEventRepository = $alumniEventRepository;
        $this->donationRepository = $donationRepository;
        $this->jobPostRepository = $jobPostRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->alumniRepository;
    }

    public function register(array $data): Alumni
    {
        return DB::transaction(function () use ($data) {
            $data['is_connected'] = true;
            $data['is_verified'] = $data['is_verified'] ?? false;

            $alumni = $this->alumniRepository->create($data);

            $this->logActivity('alumni_registered', $alumni);

            return $alumni;
        });
    }

    public function verify(int $id): Alumni
    {
        return DB::transaction(function () use ($id) {
            $alumni = $this->alumniRepository->getById($id);
            $alumni = $this->alumniRepository->update($id, ['is_verified' => true, 'verified_at' => now()]);

            $this->logActivity('alumni_verified', $alumni);

            return $alumni;
        });
    }

    public function createEvent(array $data): \App\Models\Alumni\AlumniEvent
    {
        return DB::transaction(function () use ($data) {
            $data['status'] = $data['status'] ?? 'draft';
            $event = $this->alumniEventRepository->create($data);

            $this->logActivity('alumni_event_created', $event);

            return $event;
        });
    }

    public function manageDonation(array $data): \App\Models\Alumni\AlumniDonation
    {
        return DB::transaction(function () use ($data) {
            $donation = $this->donationRepository->recordDonation($data);

            $this->logActivity('alumni_donation_recorded', $donation);

            return $donation;
        });
    }

    public function postJob(array $data): \App\Models\Alumni\JobPost
    {
        return DB::transaction(function () use ($data) {
            $data['posted_date'] = $data['posted_date'] ?? now();
            $data['is_active'] = true;

            $jobPost = $this->jobPostRepository->create($data);

            $this->logActivity('alumni_job_posted', $jobPost);

            return $jobPost;
        });
    }

    public function getAlumniDirectory(?int $graduationYear = null, ?string $industry = null): Collection
    {
        $query = $this->alumniRepository->query()->where('is_connected', true);

        if ($graduationYear) {
            $query->where('graduation_year', $graduationYear);
        }
        if ($industry) {
            $query->where('industry', $industry);
        }

        $alumni = $query->get();

        $this->logActivity('alumni_directory_viewed', ['count' => $alumni->count()]);

        return $alumni;
    }
}
