<?php

namespace App\Services\FrontOffice;

use App\Contracts\RepositoryInterface;
use App\Models\FrontOffice\Visitor;
use App\Repositories\FrontOffice\VisitorRepository;
use App\Repositories\FrontOffice\EnquiryRepository;
use App\Repositories\FrontOffice\CallLogRepository;
use App\Repositories\FrontOffice\AppointmentRepository;
use App\Repositories\FrontOffice\ComplaintRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class FrontOfficeService extends BaseService
{
    protected VisitorRepository $visitorRepository;
    protected EnquiryRepository $enquiryRepository;
    protected CallLogRepository $callLogRepository;
    protected AppointmentRepository $appointmentRepository;
    protected ComplaintRepository $complaintRepository;

    public function __construct(
        VisitorRepository $visitorRepository,
        EnquiryRepository $enquiryRepository,
        CallLogRepository $callLogRepository,
        AppointmentRepository $appointmentRepository,
        ComplaintRepository $complaintRepository
    ) {
        parent::__construct();
        $this->visitorRepository = $visitorRepository;
        $this->enquiryRepository = $enquiryRepository;
        $this->callLogRepository = $callLogRepository;
        $this->appointmentRepository = $appointmentRepository;
        $this->complaintRepository = $complaintRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->visitorRepository;
    }

    public function logVisitor(array $data): Visitor
    {
        return DB::transaction(function () use ($data) {
            $data['visit_date'] = $data['visit_date'] ?? now();
            $data['status'] = 'checked_in';

            $visitor = $this->visitorRepository->create($data);

            $this->logActivity('visitor_logged', $visitor);

            return $visitor;
        });
    }

    public function createEnquiry(array $data): \App\Models\FrontOffice\Enquiry
    {
        return DB::transaction(function () use ($data) {
            $data['enquiry_date'] = $data['enquiry_date'] ?? now();
            $data['status'] = $data['status'] ?? 'pending';

            $enquiry = $this->enquiryRepository->create($data);

            $this->logActivity('front_office_enquiry_created', $enquiry);

            return $enquiry;
        });
    }

    public function logCall(array $data): \App\Models\FrontOffice\CallLog
    {
        return DB::transaction(function () use ($data) {
            $data['call_date'] = $data['call_date'] ?? now();
            $data['status'] = $data['status'] ?? 'pending';

            $callLog = $this->callLogRepository->create($data);

            $this->logActivity('call_logged', $callLog);

            return $callLog;
        });
    }

    public function scheduleAppointment(array $data): \App\Models\FrontOffice\Appointment
    {
        return DB::transaction(function () use ($data) {
            $data['status'] = 'pending';

            if (isset($data['employee_id'])) {
                $isAvailable = $this->appointmentRepository->checkAvailability(
                    $data['employee_id'],
                    $data['appointment_date'],
                    $data['start_time'],
                    $data['end_time']
                );

                if (!$isAvailable) {
                    throw new \App\Exceptions\ServiceException("The employee is not available during this time slot.");
                }
            }

            $appointment = $this->appointmentRepository->create($data);

            $this->logActivity('appointment_scheduled', $appointment);

            return $appointment;
        });
    }

    public function manageComplaint(array $data): \App\Models\FrontOffice\Complaint
    {
        return DB::transaction(function () use ($data) {
            $data['complaint_date'] = $data['complaint_date'] ?? now();
            $data['status'] = 'pending';

            $complaint = $this->complaintRepository->create($data);

            $this->logActivity('complaint_logged', $complaint);

            return $complaint;
        });
    }
}
