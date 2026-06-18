<?php

namespace App\Services\FrontOffice;

use App\Contracts\RepositoryInterface;
use App\Models\FrontOffice\Complaint;
use App\Repositories\FrontOffice\ComplaintRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class ComplaintService extends BaseService
{
    protected ComplaintRepository $complaintRepository;

    public function __construct(ComplaintRepository $complaintRepository)
    {
        parent::__construct();
        $this->complaintRepository = $complaintRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->complaintRepository;
    }

    public function submitComplaint(array $data): Complaint
    {
        return DB::transaction(function () use ($data) {
            $data['complaint_date'] = $data['complaint_date'] ?? now();
            $data['status'] = 'pending';

            $complaint = $this->complaintRepository->create($data);

            $this->logActivity('complaint_submitted', $complaint);

            return $complaint;
        });
    }

    public function assignComplaint(int $complaintId, int $userId): Complaint
    {
        return DB::transaction(function () use ($complaintId, $userId) {
            $complaint = $this->complaintRepository->getById($complaintId);

            if ($complaint->status === 'resolved') {
                throw new \App\Exceptions\ServiceException("Cannot assign a resolved complaint.");
            }

            $this->complaintRepository->assignTo($complaintId, $userId);
            $complaint = $complaint->fresh();

            $this->logActivity('complaint_assigned', $complaint);

            return $complaint;
        });
    }

    public function resolveComplaint(int $complaintId, string $resolution): Complaint
    {
        return DB::transaction(function () use ($complaintId, $resolution) {
            $complaint = $this->complaintRepository->getById($complaintId);

            if ($complaint->status === 'resolved') {
                throw new \App\Exceptions\ServiceException("Complaint is already resolved.");
            }

            $this->complaintRepository->resolveComplaint($complaintId, $resolution);
            $complaint = $complaint->fresh();

            $this->logActivity('complaint_resolved', $complaint);

            return $complaint;
        });
    }
}
