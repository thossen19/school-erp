<?php

namespace App\Services\Admission;

use App\Contracts\RepositoryInterface;
use App\Exceptions\ServiceException;
use App\Models\Admission\AdmissionEnquiry;
use App\Repositories\Admission\AdmissionEnquiryRepository;
use App\Repositories\Admission\AdmissionFormRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class AdmissionEnquiryService extends BaseService
{
    protected AdmissionEnquiryRepository $enquiryRepository;
    protected AdmissionFormRepository $admissionFormRepository;

    public function __construct(
        AdmissionEnquiryRepository $enquiryRepository,
        AdmissionFormRepository $admissionFormRepository
    ) {
        parent::__construct();
        $this->enquiryRepository = $enquiryRepository;
        $this->admissionFormRepository = $admissionFormRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->enquiryRepository;
    }

    public function createEnquiry(array $data): AdmissionEnquiry
    {
        return DB::transaction(function () use ($data) {
            $data['enquiry_date'] = $data['enquiry_date'] ?? now();
            $data['status'] = $data['status'] ?? 'open';

            $enquiry = $this->enquiryRepository->create($data);

            $this->logActivity('enquiry_created', $enquiry);

            return $enquiry;
        });
    }

    public function updateEnquiry(int $id, array $data): AdmissionEnquiry
    {
        return DB::transaction(function () use ($id, $data) {
            $enquiry = $this->enquiryRepository->getById($id);

            if (isset($data['status']) && $data['status'] === 'closed') {
                $data['closed_at'] = now();
            }

            $enquiry = $this->enquiryRepository->update($id, $data);

            $this->logActivity('enquiry_updated', $enquiry);

            return $enquiry;
        });
    }

    public function convertToApplication(int $id, array $applicationData): array
    {
        return DB::transaction(function () use ($id, $applicationData) {
            $enquiry = $this->enquiryRepository->getById($id);

            $formData = array_merge([
                'applicant_name' => $enquiry->student_name,
                'parent_name' => $enquiry->parent_name,
                'parent_phone' => $enquiry->parent_phone,
                'parent_email' => $enquiry->parent_email,
                'class_id' => $enquiry->class_id,
                'status' => 'pending',
                'source' => 'enquiry',
                'source_enquiry_id' => $enquiry->id,
            ], $applicationData);

            $application = $this->admissionFormRepository->create($formData);

            $this->enquiryRepository->update($id, [
                'status' => 'converted',
                'converted_at' => now(),
                'converted_to_application_id' => $application->id,
            ]);

            $this->logActivity('enquiry_converted_to_application', [
                'enquiry_id' => $id,
                'application_id' => $application->id,
            ]);

            return [
                'enquiry' => $enquiry->fresh(),
                'application' => $application,
            ];
        });
    }

    public function followUp(int $id, array $data): AdmissionEnquiry
    {
        return DB::transaction(function () use ($id, $data) {
            $enquiry = $this->enquiryRepository->getById($id);

            $followUpData = [
                'last_follow_up' => now(),
                'follow_up_date' => $data['next_follow_up_date'] ?? null,
                'follow_up_notes' => $data['notes'] ?? null,
                'followed_up_by' => $data['followed_up_by'] ?? auth()->id(),
            ];

            if (isset($data['status'])) {
                $followUpData['status'] = $data['status'];
            }

            $enquiry = $this->enquiryRepository->update($id, $followUpData);

            $this->logActivity('enquiry_follow_up', $enquiry);

            return $enquiry;
        });
    }
}
