<?php

namespace App\Providers;

use App\Contracts\RepositoryInterface;
use App\Repositories\Academic\AssignmentRepository;
use App\Repositories\Academic\ClassRepository;
use App\Repositories\Academic\ClassSubjectRepository;
use App\Repositories\Academic\HomeworkRepository;
use App\Repositories\Academic\LessonPlanRepository;
use App\Repositories\Academic\SectionRepository;
use App\Repositories\Academic\StudyMaterialRepository;
use App\Repositories\Academic\SubjectRepository;
use App\Repositories\Accounting\BankReconciliationRepository;
use App\Repositories\Accounting\BudgetRepository;
use App\Repositories\Accounting\ChartOfAccountRepository;
use App\Repositories\Accounting\JournalEntryRepository;
use App\Repositories\Admission\AdmissionEnquiryRepository;
use App\Repositories\Admission\AdmissionFormRepository;
use App\Repositories\Admission\EntranceExamRepository;
use App\Repositories\Admission\EntranceExamResultRepository;
use App\Repositories\Admission\MeritListRepository;
use App\Repositories\Admission\WaitingListRepository;
use App\Repositories\Alumni\AlumniDonationRepository;
use App\Repositories\Alumni\AlumniEventRepository;
use App\Repositories\Alumni\AlumniRepository;
use App\Repositories\Alumni\JobPostRepository;
use App\Repositories\Assessment\ContinuousAssessmentRepository;
use App\Repositories\Assessment\ExamRepository;
use App\Repositories\Assessment\ExamResultRepository;
use App\Repositories\Assessment\ExamScheduleRepository;
use App\Repositories\Assessment\GradingSystemRepository;
use App\Repositories\Asset\AssetAllocationRepository;
use App\Repositories\Asset\AssetDepreciationRepository;
use App\Repositories\Asset\AssetMaintenanceRepository;
use App\Repositories\Asset\AssetRepository;
use App\Repositories\Attendance\AttendanceRepository;
use App\Repositories\Attendance\AttendanceSettingRepository;
use App\Repositories\Attendance\HolidayRepository;
use App\Repositories\Attendance\LeaveBalanceRepository;
use App\Repositories\Attendance\LeaveRequestRepository;
use App\Repositories\Attendance\LeaveTypeRepository;
use App\Repositories\BaseRepository;
use App\Repositories\Certificate\CertificateRepository;
use App\Repositories\Certificate\CertificateTemplateRepository;
use App\Repositories\Certificate\CertificateTypeRepository;
use App\Repositories\Events\ClubRepository;
use App\Repositories\Events\EventRegistrationRepository;
use App\Repositories\Events\EventRepository;
use App\Repositories\Fee\FeeCategoryRepository;
use App\Repositories\Fee\FeeCollectionRepository;
use App\Repositories\Fee\FeeConcessionRepository;
use App\Repositories\Fee\FeeDiscountRepository;
use App\Repositories\Fee\FeeDueTrackingRepository;
use App\Repositories\Fee\FeeInstallmentRepository;
use App\Repositories\Fee\FeeStructureRepository;
use App\Repositories\Fee\ScholarshipRepository;
use App\Repositories\FrontOffice\AppointmentRepository;
use App\Repositories\FrontOffice\CallLogRepository;
use App\Repositories\FrontOffice\ComplaintRepository;
use App\Repositories\FrontOffice\EnquiryRepository;
use App\Repositories\FrontOffice\VisitorRepository;
use App\Repositories\Health\HealthRecordRepository;
use App\Repositories\Health\MedicineRepository;
use App\Repositories\Health\VaccinationRecordRepository;
use App\Repositories\Hostel\HostelAllocationRepository;
use App\Repositories\Hostel\HostelRepository;
use App\Repositories\Hostel\HostelRoomRepository;
use App\Repositories\Hostel\HostelVisitorRepository;
use App\Repositories\Hr\DepartmentRepository;
use App\Repositories\Hr\DesignationRepository;
use App\Repositories\Hr\EmployeeRepository;
use App\Repositories\Hr\JobApplicationRepository;
use App\Repositories\Hr\RecruitmentRepository;
use App\Repositories\Inventory\ItemRepository;
use App\Repositories\Inventory\PurchaseOrderRepository;
use App\Repositories\Inventory\StockMovementRepository;
use App\Repositories\Inventory\VendorRepository;
use App\Repositories\Library\BookIssueRepository;
use App\Repositories\Library\BookRepository;
use App\Repositories\Library\LibraryMemberRepository;
use App\Repositories\Payroll\LoanRequestRepository;
use App\Repositories\Payroll\OvertimeRecordRepository;
use App\Repositories\Payroll\PayrollRepository;
use App\Repositories\Payroll\SalaryStructureRepository;
use App\Repositories\Student\StudentParentRepository;
use App\Repositories\Student\StudentAwardRepository;
use App\Repositories\Student\StudentDisciplineRepository;
use App\Repositories\Student\StudentDocumentRepository;
use App\Repositories\Student\StudentGroupRepository;
use App\Repositories\Student\StudentHouseRepository;
use App\Repositories\Student\StudentMedicalRecordRepository;
use App\Repositories\Student\StudentPromotionRepository;
use App\Repositories\Student\StudentRepository;
use App\Repositories\Student\StudentTransferRepository;
use App\Repositories\Timetable\RoomAllocationRepository;
use App\Repositories\Timetable\SubstitutionRequestRepository;
use App\Repositories\Timetable\TimetableAllocationRepository;
use App\Repositories\Timetable\TimetablePeriodRepository;
use App\Repositories\Timetable\TimetableRepository;
use App\Repositories\Transport\TransportAllocationRepository;
use App\Repositories\Transport\TransportDriverRepository;
use App\Repositories\Transport\TransportRouteRepository;
use App\Repositories\Transport\TransportTrackingRepository;
use App\Repositories\Transport\TransportVehicleRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    protected array $repositories = [
        StudentRepository::class,
        StudentParentRepository::class,
        StudentAwardRepository::class,
        StudentDisciplineRepository::class,
        StudentDocumentRepository::class,
        StudentGroupRepository::class,
        StudentHouseRepository::class,
        StudentMedicalRecordRepository::class,
        StudentPromotionRepository::class,
        StudentTransferRepository::class,
        AttendanceRepository::class,
        AttendanceSettingRepository::class,
        HolidayRepository::class,
        LeaveBalanceRepository::class,
        LeaveRequestRepository::class,
        LeaveTypeRepository::class,
        FeeCategoryRepository::class,
        FeeCollectionRepository::class,
        FeeConcessionRepository::class,
        FeeDiscountRepository::class,
        FeeDueTrackingRepository::class,
        FeeInstallmentRepository::class,
        FeeStructureRepository::class,
        ScholarshipRepository::class,
        ExamRepository::class,
        ExamResultRepository::class,
        ExamScheduleRepository::class,
        GradingSystemRepository::class,
        ContinuousAssessmentRepository::class,
        EmployeeRepository::class,
        DepartmentRepository::class,
        DesignationRepository::class,
        JobApplicationRepository::class,
        RecruitmentRepository::class,
        PayrollRepository::class,
        SalaryStructureRepository::class,
        LoanRequestRepository::class,
        OvertimeRecordRepository::class,
        ClassRepository::class,
        SectionRepository::class,
        SubjectRepository::class,
        ClassSubjectRepository::class,
        AssignmentRepository::class,
        HomeworkRepository::class,
        LessonPlanRepository::class,
        StudyMaterialRepository::class,
        TimetableRepository::class,
        TimetablePeriodRepository::class,
        TimetableAllocationRepository::class,
        RoomAllocationRepository::class,
        SubstitutionRequestRepository::class,
        EventRepository::class,
        EventRegistrationRepository::class,
        ClubRepository::class,
        VisitorRepository::class,
        EnquiryRepository::class,
        CallLogRepository::class,
        AppointmentRepository::class,
        ComplaintRepository::class,
        HealthRecordRepository::class,
        VaccinationRecordRepository::class,
        MedicineRepository::class,
        AdmissionEnquiryRepository::class,
        AdmissionFormRepository::class,
        EntranceExamRepository::class,
        EntranceExamResultRepository::class,
        MeritListRepository::class,
        WaitingListRepository::class,
        ChartOfAccountRepository::class,
        JournalEntryRepository::class,
        BankReconciliationRepository::class,
        BudgetRepository::class,
        TransportRouteRepository::class,
        TransportVehicleRepository::class,
        TransportDriverRepository::class,
        TransportAllocationRepository::class,
        TransportTrackingRepository::class,
        ItemRepository::class,
        StockMovementRepository::class,
        PurchaseOrderRepository::class,
        VendorRepository::class,
        BookRepository::class,
        BookIssueRepository::class,
        LibraryMemberRepository::class,
        AlumniRepository::class,
        AlumniEventRepository::class,
        AlumniDonationRepository::class,
        JobPostRepository::class,
        HostelRepository::class,
        HostelRoomRepository::class,
        HostelAllocationRepository::class,
        HostelVisitorRepository::class,
        AssetRepository::class,
        AssetAllocationRepository::class,
        AssetMaintenanceRepository::class,
        AssetDepreciationRepository::class,
        CertificateRepository::class,
        CertificateTemplateRepository::class,
        CertificateTypeRepository::class,
    ];

    public function register(): void
    {
        $this->app->bind(RepositoryInterface::class, BaseRepository::class);

        foreach ($this->repositories as $repository) {
            $this->app->singleton($repository);
        }
    }

    public function boot(): void
    {
        //
    }
}
