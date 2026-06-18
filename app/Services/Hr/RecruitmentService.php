<?php

namespace App\Services\Hr;

use App\Contracts\RepositoryInterface;
use App\Models\Hr\Recruitment;
use App\Models\Hr\JobApplication;
use App\Repositories\Hr\RecruitmentRepository;
use App\Repositories\Hr\JobApplicationRepository;
use App\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RecruitmentService extends BaseService
{
    protected RecruitmentRepository $recruitmentRepository;
    protected JobApplicationRepository $applicationRepository;

    public function __construct(
        RecruitmentRepository $recruitmentRepository,
        JobApplicationRepository $applicationRepository
    ) {
        parent::__construct();
        $this->recruitmentRepository = $recruitmentRepository;
        $this->applicationRepository = $applicationRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->recruitmentRepository;
    }

    public function postJob(array $data): Recruitment
    {
        return DB::transaction(function () use ($data) {
            $data['status'] = $data['status'] ?? 'open';
            $data['posted_date'] = $data['posted_date'] ?? now();

            $recruitment = $this->recruitmentRepository->create($data);

            $this->logActivity('job_posted', $recruitment);

            return $recruitment;
        });
    }

    public function receiveApplication(array $data): JobApplication
    {
        return DB::transaction(function () use ($data) {
            $recruitment = $this->recruitmentRepository->getById($data['recruitment_id']);

            $existing = $this->applicationRepository->findByEmail($data['email']);
            if ($existing && $existing->recruitment_id === (int)$data['recruitment_id']) {
                throw new \App\Exceptions\ServiceException("You have already applied for this position.");
            }

            $data['applied_date'] = $data['applied_date'] ?? now();
            $data['status'] = 'pending';

            $application = $this->applicationRepository->create($data);

            $this->logActivity('job_application_received', $application);

            return $application;
        });
    }

    public function shortlist(int $applicationId): JobApplication
    {
        return DB::transaction(function () use ($applicationId) {
            $application = $this->applicationRepository->getById($applicationId);
            $this->applicationRepository->shortlist($applicationId);

            $application = $application->fresh();

            $this->logActivity('applicant_shortlisted', $application);

            return $application;
        });
    }

    public function scheduleInterview(int $applicationId, array $data): JobApplication
    {
        return DB::transaction(function () use ($applicationId, $data) {
            $application = $this->applicationRepository->getById($applicationId);

            $application = $this->applicationRepository->update($applicationId, [
                'interview_date' => $data['interview_date'],
                'interview_time' => $data['interview_time'] ?? null,
                'interview_venue' => $data['interview_venue'] ?? null,
                'interviewer_id' => $data['interviewer_id'] ?? null,
                'status' => 'interview_scheduled',
            ]);

            $this->logActivity('interview_scheduled_for_applicant', $application);

            return $application;
        });
    }

    public function makeOffer(int $applicationId, array $offerData): JobApplication
    {
        return DB::transaction(function () use ($applicationId, $offerData) {
            $application = $this->applicationRepository->getById($applicationId);

            $application = $this->applicationRepository->update($applicationId, array_merge($offerData, [
                'status' => 'offered',
                'offer_date' => now(),
                'offered_by' => auth()->id(),
            ]));

            $this->logActivity('job_offer_made', $application);

            return $application;
        });
    }
}
