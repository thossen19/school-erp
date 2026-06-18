<?php

use App\Http\Controllers\Api\V1\AccountingController;
use App\Http\Controllers\Api\V1\ActivityLogController;
use App\Http\Controllers\Api\V1\AdmissionController;
use App\Http\Controllers\Api\V1\AdmissionEnquiryController;
use App\Http\Controllers\Api\V1\AlumniController;
use App\Http\Controllers\Api\V1\AlumniDonationController;
use App\Http\Controllers\Api\V1\AlumniEventController;
use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\Api\V1\AssetCategoryController;
use App\Http\Controllers\Api\V1\AssetController;
use App\Http\Controllers\Api\V1\AssetMaintenanceController;
use App\Http\Controllers\Api\V1\AssignmentController;
use App\Http\Controllers\Api\V1\AttendanceController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BackupController;
use App\Http\Controllers\Api\V1\BookController;
use App\Http\Controllers\Api\V1\BookIssueController;
use App\Http\Controllers\Api\V1\BranchController;
use App\Http\Controllers\Api\V1\BudgetController;
use App\Http\Controllers\Api\V1\CallLogController;
use App\Http\Controllers\Api\V1\CertificateController;
use App\Http\Controllers\Api\V1\CertificateTemplateController;
use App\Http\Controllers\Api\V1\ChartOfAccountController;
use App\Http\Controllers\Api\V1\ClassController;
use App\Http\Controllers\Api\V1\ClubController;
use App\Http\Controllers\Api\V1\ComplaintController;
use App\Http\Controllers\Api\V1\ContinuousAssessmentController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\DepartmentController;
use App\Http\Controllers\Api\V1\EmployeeController;
use App\Http\Controllers\Api\V1\EnquiryController;
use App\Http\Controllers\Api\V1\EntranceExamController;
use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\ExamController;
use App\Http\Controllers\Api\V1\ExamResultController;
use App\Http\Controllers\Api\V1\FeeCategoryController;
use App\Http\Controllers\Api\V1\FeeCollectionController;
use App\Http\Controllers\Api\V1\FeeDiscountController;
use App\Http\Controllers\Api\V1\FeeStructureController;
use App\Http\Controllers\Api\V1\GradingSystemController;
use App\Http\Controllers\Api\V1\HealthRecordController;
use App\Http\Controllers\Api\V1\HolidayController;
use App\Http\Controllers\Api\V1\HomeworkController;
use App\Http\Controllers\Api\V1\HostelAllocationController;
use App\Http\Controllers\Api\V1\HostelController;
use App\Http\Controllers\Api\V1\HostelRoomController;
use App\Http\Controllers\Api\V1\HostelVisitorController;
use App\Http\Controllers\Api\V1\ItemController;
use App\Http\Controllers\Api\V1\JobPostController;
use App\Http\Controllers\Api\V1\LeaveController;
use App\Http\Controllers\Api\V1\LessonPlanController;
use App\Http\Controllers\Api\V1\LibraryFineController;
use App\Http\Controllers\Api\V1\LibraryMemberController;
use App\Http\Controllers\Api\V1\LoanController;
use App\Http\Controllers\Api\V1\MedicineController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\ParentController;
use App\Http\Controllers\Api\V1\PayrollController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\PurchaseOrderController;
use App\Http\Controllers\Api\V1\RecruitmentController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Api\V1\RoomAllocationController;
use App\Http\Controllers\Api\V1\SalaryStructureController;
use App\Http\Controllers\Api\V1\ScholarshipController;
use App\Http\Controllers\Api\V1\SchoolController;
use App\Http\Controllers\Api\V1\SearchController;
use App\Http\Controllers\Api\V1\SectionController;
use App\Http\Controllers\Api\V1\SettingsController;
use App\Http\Controllers\Api\V1\StockAuditController;
use App\Http\Controllers\Api\V1\StudentController;
use App\Http\Controllers\Api\V1\StudentHouseController;
use App\Http\Controllers\Api\V1\StudentMedicalController;
use App\Http\Controllers\Api\V1\StudyMaterialController;
use App\Http\Controllers\Api\V1\SubjectController;
use App\Http\Controllers\Api\V1\SubstitutionController;
use App\Http\Controllers\Api\V1\TeacherDiaryController;
use App\Http\Controllers\Api\V1\TimetableController;
use App\Http\Controllers\Api\V1\TransportAllocationController;
use App\Http\Controllers\Api\V1\TransportDriverController;
use App\Http\Controllers\Api\V1\TransportRouteController;
use App\Http\Controllers\Api\V1\TransportTrackingController;
use App\Http\Controllers\Api\V1\TransportVehicleController;
use App\Http\Controllers\Api\V1\VaccinationController;
use App\Http\Controllers\Api\V1\VendorController;
use App\Http\Controllers\Api\V1\VisitorController;
use Illuminate\Support\Facades\Route;

// Public Auth Routes
Route::prefix('v1/auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
});

// Protected API Routes
Route::prefix('v1')->middleware(['auth:sanctum', 'school'])->group(function () {

    // Auth (authenticated)
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refreshToken']);
        Route::get('me', [AuthController::class, 'me']);
        Route::post('update-profile', [AuthController::class, 'updateProfile']);
        Route::post('change-password', [AuthController::class, 'changePassword']);
        Route::post('verify-email', [AuthController::class, 'verifyEmail']);
    });

    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);
        Route::post('/update', [ProfileController::class, 'update']);
        Route::post('/change-password', [ProfileController::class, 'changePassword']);
        Route::post('/upload-avatar', [ProfileController::class, 'uploadAvatar']);
        Route::post('/update-theme', [ProfileController::class, 'updateTheme']);
        Route::post('/update-language', [ProfileController::class, 'updateLanguage']);
    });

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::get('dashboard/student-stats', [DashboardController::class, 'studentStats']);
    Route::get('dashboard/admission-stats', [DashboardController::class, 'admissionStats']);
    Route::get('dashboard/attendance-analytics', [DashboardController::class, 'attendanceAnalytics']);
    Route::get('dashboard/fee-report', [DashboardController::class, 'feeReport']);
    Route::get('dashboard/payroll-summary', [DashboardController::class, 'payrollSummary']);
    Route::get('dashboard/academic-performance', [DashboardController::class, 'academicPerformance']);

    // School Management
    Route::apiResource('schools', SchoolController::class);
    Route::post('schools/{school}/branches', [SchoolController::class, 'manageBranches']);
    Route::match(['get', 'post'], 'schools/{school}/academic-years', [SchoolController::class, 'manageAcademicYears']);
    Route::get('schools/{school}/settings', [SchoolController::class, 'getSettings']);
    Route::put('schools/{school}/settings', [SchoolController::class, 'updateSettings']);

    // Branches
    Route::apiResource('branches', BranchController::class);

    // Admissions
    Route::prefix('admissions')->group(function () {
        Route::get('/', [AdmissionController::class, 'index']);
        Route::post('/', [AdmissionController::class, 'store']);
        Route::get('applications', [AdmissionController::class, 'applications']);
        Route::get('statistics', [AdmissionController::class, 'getStatistics']);
        Route::get('enquiries', [AdmissionController::class, 'getEnquiries']);
        Route::post('merit-list/generate', [AdmissionController::class, 'generateMeritList']);
        Route::get('{admission}', [AdmissionController::class, 'show']);
        Route::put('{admission}', [AdmissionController::class, 'update']);
        Route::delete('{admission}', [AdmissionController::class, 'destroy']);
        Route::post('{admission}/approve', [AdmissionController::class, 'approve']);
        Route::post('{admission}/reject', [AdmissionController::class, 'reject']);
        Route::post('{admission}/schedule-interview', [AdmissionController::class, 'scheduleInterview']);
        Route::post('{admission}/waiting-list', [AdmissionController::class, 'addToWaitingList']);
    });

    // Admission Enquiries
    Route::apiResource('admission-enquiries', AdmissionEnquiryController::class);
    Route::post('admission-enquiries/{enquiry}/convert', [AdmissionEnquiryController::class, 'convertToApplication']);
    Route::post('admission-enquiries/{enquiry}/follow-up', [AdmissionEnquiryController::class, 'followUp']);

    // Entrance Exams
    Route::apiResource('entrance-exams', EntranceExamController::class);
    Route::post('entrance-exams/{exam}/publish-results', [EntranceExamController::class, 'publishResults']);
    Route::get('entrance-exams/{exam}/rankings', [EntranceExamController::class, 'generateRankings']);

    // Students
    Route::prefix('students')->group(function () {
        Route::get('/', [StudentController::class, 'index']);
        Route::post('/', [StudentController::class, 'store']);
        Route::get('search', [StudentController::class, 'search']);
        Route::get('statistics', [StudentController::class, 'getStatistics']);
        Route::get('by-class/{class}', [StudentController::class, 'getByClass']);
        Route::get('by-class/{class}/section/{section}', [StudentController::class, 'getBySection']);
        Route::get('by-house/{house}', [StudentController::class, 'getByHouse']);
        Route::get('{student}', [StudentController::class, 'show']);
        Route::put('{student}', [StudentController::class, 'update']);
        Route::delete('{student}', [StudentController::class, 'destroy']);
        Route::post('{student}/promote', [StudentController::class, 'promote']);
        Route::post('{student}/transfer', [StudentController::class, 'transfer']);
        Route::post('{student}/documents', [StudentController::class, 'addDocument']);
        Route::post('{student}/discipline', [StudentController::class, 'addDiscipline']);
        Route::post('{student}/awards', [StudentController::class, 'addAward']);
        Route::get('{student}/timeline', [StudentController::class, 'getTimeline']);
        Route::get('{student}/profile', [StudentController::class, 'getProfile']);
    });

    // Parents
    Route::apiResource('parents', ParentController::class);
    Route::post('parents/{parent}/link-student', [ParentController::class, 'linkToStudent']);
    Route::get('parents/{parent}/children', [ParentController::class, 'getChildren']);

    // Student Medical
    Route::get('students/{student}/medical', [StudentMedicalController::class, 'getRecord']);
    Route::put('students/{student}/medical', [StudentMedicalController::class, 'updateRecord']);
    Route::post('students/{student}/vaccinations', [StudentMedicalController::class, 'addVaccination']);
    Route::get('students/{student}/health-report', [StudentMedicalController::class, 'getHealthReport']);

    // Student Houses
    Route::apiResource('student-houses', StudentHouseController::class);
    Route::post('student-houses/{house}/assign-students', [StudentHouseController::class, 'assignStudents']);
    Route::get('student-houses/{house}/report', [StudentHouseController::class, 'getReport']);

    // Attendance
    Route::prefix('attendance')->group(function () {
        Route::get('/', [AttendanceController::class, 'index']);
        Route::post('/', [AttendanceController::class, 'store']);
        Route::post('bulk', [AttendanceController::class, 'bulkMark']);
        Route::get('by-date/{date}', [AttendanceController::class, 'getByDate']);
        Route::get('by-student/{student}', [AttendanceController::class, 'getByStudent']);
        Route::get('by-class/{class}', [AttendanceController::class, 'getByClass']);
        Route::get('report', [AttendanceController::class, 'getReport']);
        Route::post('correction', [AttendanceController::class, 'requestCorrection']);
        Route::post('correction/{correction}/approve', [AttendanceController::class, 'approveCorrection']);
        Route::get('analytics', [AttendanceController::class, 'getAnalytics']);
    });

    // Leave
    Route::get('leaves', [LeaveController::class, 'index']);
    Route::post('leaves', [LeaveController::class, 'store']);
    Route::post('leaves/{leave}/approve', [LeaveController::class, 'approve']);
    Route::post('leaves/{leave}/reject', [LeaveController::class, 'reject']);
    Route::get('leaves/balance', [LeaveController::class, 'getBalance']);
    Route::get('leaves/calendar', [LeaveController::class, 'getCalendar']);

    // Holidays
    Route::apiResource('holidays', HolidayController::class);

    // Fee Management
    Route::apiResource('fee-categories', FeeCategoryController::class);
    Route::apiResource('fee-structures', FeeStructureController::class);
    Route::get('fee-structures/by-class/{class}', [FeeStructureController::class, 'getByClass']);

    Route::prefix('fee-collections')->group(function () {
        Route::get('/', [FeeCollectionController::class, 'index']);
        Route::post('/', [FeeCollectionController::class, 'store']);
        Route::get('due-payments', [FeeCollectionController::class, 'getDuePayments']);
        Route::get('report', [FeeCollectionController::class, 'getReport']);
        Route::get('by-student/{student}', [FeeCollectionController::class, 'getByStudent']);
        Route::get('{collection}', [FeeCollectionController::class, 'show']);
        Route::post('{collection}/receipt', [FeeCollectionController::class, 'generateReceipt']);
        Route::post('{collection}/reconcile', [FeeCollectionController::class, 'reconcilePayment']);
        Route::post('{collection}/reminder', [FeeCollectionController::class, 'sendReminder']);
    });

    Route::apiResource('fee-discounts', FeeDiscountController::class);
    Route::post('fee-discounts/{discount}/apply', [FeeDiscountController::class, 'applyToStudent']);
    Route::apiResource('scholarships', ScholarshipController::class);
    Route::post('scholarships/{scholarship}/award', [ScholarshipController::class, 'award']);
    Route::post('scholarships/{scholarship}/revoke/{student}', [ScholarshipController::class, 'revoke']);

    // Timetable
    Route::apiResource('timetables', TimetableController::class);
    Route::post('timetables/generate-ai', [TimetableController::class, 'generateAI']);
    Route::post('timetables/detect-conflicts', [TimetableController::class, 'detectConflicts']);
    Route::get('timetables/by-class/{class}/{section?}', [TimetableController::class, 'getClassTimetable']);
    Route::get('timetables/by-teacher/{teacher}', [TimetableController::class, 'getTeacherTimetable']);

    // Rooms
    Route::apiResource('rooms', RoomAllocationController::class);
    Route::post('rooms/check-availability', [RoomAllocationController::class, 'checkAvailability']);

    // Substitutions
    Route::apiResource('substitutions', SubstitutionController::class);
    Route::post('substitutions/{substitution}/approve', [SubstitutionController::class, 'approve']);
    Route::post('substitutions/{substitution}/reject', [SubstitutionController::class, 'reject']);

    // Exams
    Route::prefix('exams')->group(function () {
        Route::get('/', [ExamController::class, 'index']);
        Route::post('/', [ExamController::class, 'store']);
        Route::get('{exam}', [ExamController::class, 'show']);
        Route::put('{exam}', [ExamController::class, 'update']);
        Route::delete('{exam}', [ExamController::class, 'destroy']);
        Route::post('{exam}/schedule', [ExamController::class, 'schedule']);
        Route::post('{exam}/publish-results', [ExamController::class, 'publishResults']);
        Route::get('{exam}/rankings', [ExamController::class, 'generateRankings']);
        Route::get('{exam}/grade-card/{student}', [ExamController::class, 'getGradeCard']);
    });

    // Exam Results
    Route::apiResource('exam-results', ExamResultController::class);
    Route::post('exam-results/bulk-entry', [ExamResultController::class, 'bulkEntry']);
    Route::get('exam-results/by-exam/{exam}', [ExamResultController::class, 'getByExam']);
    Route::get('exam-results/by-student/{student}', [ExamResultController::class, 'getByStudent']);

    // Grading System
    Route::apiResource('grading-systems', GradingSystemController::class);

    // Continuous Assessment
    Route::apiResource('continuous-assessments', ContinuousAssessmentController::class);
    Route::get('continuous-assessments/progress-report/{student}', [ContinuousAssessmentController::class, 'getProgressReport']);

    // Certificates
    Route::prefix('certificates')->group(function () {
        Route::get('/', [CertificateController::class, 'index']);
        Route::post('/', [CertificateController::class, 'store']);
        Route::get('{certificate}', [CertificateController::class, 'show']);
        Route::post('{certificate}/generate', [CertificateController::class, 'generate']);
        Route::get('verify/{certificateNo}', [CertificateController::class, 'verify']);
        Route::get('{certificate}/download', [CertificateController::class, 'download']);
    });
    Route::apiResource('certificate-templates', CertificateTemplateController::class);

    // HR: Employees
    Route::prefix('employees')->group(function () {
        Route::get('/', [EmployeeController::class, 'index']);
        Route::post('/', [EmployeeController::class, 'store']);
        Route::get('search', [EmployeeController::class, 'search']);
        Route::get('directory', [EmployeeController::class, 'getDirectory']);
        Route::get('by-department/{department}', [EmployeeController::class, 'getByDepartment']);
        Route::get('by-designation/{designation}', [EmployeeController::class, 'getByDesignation']);
        Route::get('{employee}', [EmployeeController::class, 'show']);
        Route::put('{employee}', [EmployeeController::class, 'update']);
        Route::delete('{employee}', [EmployeeController::class, 'destroy']);
    });

    // Departments
    Route::apiResource('departments', DepartmentController::class);

    // Recruitment
    Route::apiResource('recruitments', RecruitmentController::class);
    Route::post('recruitments/{recruitment}/applications', [RecruitmentController::class, 'receiveApplication']);
    Route::post('recruitments/{recruitment}/applications/{application}/shortlist', [RecruitmentController::class, 'shortlist']);
    Route::post('recruitments/{recruitment}/applications/{application}/schedule-interview', [RecruitmentController::class, 'scheduleInterview']);
    Route::post('recruitments/{recruitment}/applications/{application}/make-offer', [RecruitmentController::class, 'makeOffer']);

    // Payroll
    Route::prefix('payroll')->group(function () {
        Route::get('/', [PayrollController::class, 'index']);
        Route::post('/', [PayrollController::class, 'store']);
        Route::post('process', [PayrollController::class, 'processPayroll']);
        Route::get('report', [PayrollController::class, 'getReport']);
        Route::get('export-tally', [PayrollController::class, 'exportTally']);
        Route::get('by-employee/{employee}', [PayrollController::class, 'getByEmployee']);
        Route::get('{payroll}', [PayrollController::class, 'show']);
        Route::post('{payroll}/payslip', [PayrollController::class, 'generatePayslip']);
    });

    // Salary Structures
    Route::apiResource('salary-structures', SalaryStructureController::class);

    // Loans
    Route::apiResource('loans', LoanController::class);
    Route::post('loans/{loan}/approve', [LoanController::class, 'approve']);
    Route::post('loans/{loan}/deduct-installment', [LoanController::class, 'deductInstallment']);

    // Academic
    Route::apiResource('classes', ClassController::class);
    Route::get('classes/{class}/sections', [ClassController::class, 'getSections']);
    Route::apiResource('sections', SectionController::class);
    Route::apiResource('subjects', SubjectController::class);
    Route::post('subjects/{subject}/assign-class', [SubjectController::class, 'assignToClass']);
    Route::post('subjects/{subject}/assign-teacher', [SubjectController::class, 'assignToTeacher']);
    Route::apiResource('lesson-plans', LessonPlanController::class);
    Route::apiResource('assignments', AssignmentController::class);
    Route::post('assignments/{assignment}/submit', [AssignmentController::class, 'submit']);
    Route::post('assignments/{assignment}/submissions/{submission}/grade', [AssignmentController::class, 'grade']);
    Route::get('assignments/{assignment}/submissions', [AssignmentController::class, 'getSubmissions']);
    Route::apiResource('homework', HomeworkController::class);
    Route::apiResource('study-materials', StudyMaterialController::class);
    Route::apiResource('teacher-diary', TeacherDiaryController::class);

    // Events & Clubs
    Route::apiResource('events', EventController::class);
    Route::post('events/{event}/register', [EventController::class, 'manageRegistration']);
    Route::get('events/calendar', [EventController::class, 'getCalendar']);
    Route::apiResource('clubs', ClubController::class);
    Route::post('clubs/{club}/members', [ClubController::class, 'manageMembers']);

    // Front Office
    Route::apiResource('visitors', VisitorController::class);
    Route::post('visitors/{visitor}/check-in', [VisitorController::class, 'checkIn']);
    Route::post('visitors/{visitor}/check-out', [VisitorController::class, 'checkOut']);
    Route::apiResource('enquiries', EnquiryController::class);
    Route::apiResource('call-logs', CallLogController::class);
    Route::apiResource('appointments', AppointmentController::class);
    Route::apiResource('complaints', ComplaintController::class);
    Route::post('complaints/{complaint}/assign', [ComplaintController::class, 'assign']);
    Route::post('complaints/{complaint}/resolve', [ComplaintController::class, 'resolve']);

    // Health
    Route::apiResource('health-records', HealthRecordController::class);
    Route::get('health-records/report/{student}', [HealthRecordController::class, 'getReport']);
    Route::apiResource('vaccinations', VaccinationController::class);
    Route::apiResource('medicines', MedicineController::class);

    // MIS & Reports
    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index']);
        Route::post('generate', [ReportController::class, 'generate']);
        Route::post('schedule', [ReportController::class, 'schedule']);
        Route::get('kpis', [ReportController::class, 'getKpis']);
        Route::get('analytics', [ReportController::class, 'getAnalytics']);
        Route::get('dashboard', [ReportController::class, 'getDashboardData']);
    });

    // Accounting
    Route::prefix('accounting')->group(function () {
        Route::get('/', [AccountingController::class, 'index']);
        Route::post('journal-entries', [AccountingController::class, 'createJournalEntry']);
        Route::post('reconcile-bank', [AccountingController::class, 'reconcileBank']);
        Route::post('manage-budget', [AccountingController::class, 'manageBudget']);
        Route::get('statements', [AccountingController::class, 'getStatements']);
        Route::get('export-tally', [AccountingController::class, 'exportTally']);
    });
    Route::apiResource('chart-of-accounts', ChartOfAccountController::class);
    Route::apiResource('budgets', BudgetController::class);
    Route::get('budgets/{budget}/track-expenditure', [BudgetController::class, 'trackExpenditure']);

    // Transport
    Route::apiResource('transport-routes', TransportRouteController::class);
    Route::get('transport-routes/{route}/stops', [TransportRouteController::class, 'getStops']);
    Route::apiResource('transport-vehicles', TransportVehicleController::class);
    Route::apiResource('transport-drivers', TransportDriverController::class);
    Route::apiResource('transport-allocations', TransportAllocationController::class);
    Route::get('transport-allocations/by-student/{student}', [TransportAllocationController::class, 'getByStudent']);

    Route::prefix('transport-tracking')->group(function () {
        Route::post('location', [TransportTrackingController::class, 'updateLocation']);
        Route::get('vehicle/{vehicle}/location', [TransportTrackingController::class, 'getVehicleLocation']);
        Route::get('vehicle/{vehicle}/history', [TransportTrackingController::class, 'getRouteHistory']);
    });

    // Inventory
    Route::apiResource('items', ItemController::class);
    Route::post('items/{item}/manage-stock', [ItemController::class, 'manageStock']);
    Route::apiResource('purchase-orders', PurchaseOrderController::class);
    Route::post('purchase-orders/{order}/receive', [PurchaseOrderController::class, 'receiveOrder']);
    Route::apiResource('vendors', VendorController::class);
    Route::apiResource('stock-audits', StockAuditController::class);

    // Library
    Route::get('books', [BookController::class, 'index'])->name('api.books.index');
    Route::post('books', [BookController::class, 'store'])->name('api.books.store');
    Route::get('books/{book}', [BookController::class, 'show'])->name('api.books.show');
    Route::put('books/{book}', [BookController::class, 'update'])->name('api.books.update');
    Route::delete('books/{book}', [BookController::class, 'destroy'])->name('api.books.destroy');
    Route::get('books/search', [BookController::class, 'search'])->name('api.books.search');
    Route::get('books/by-category/{category}', [BookController::class, 'getByCategory'])->name('api.books.by-category');
    Route::apiResource('book-issues', BookIssueController::class);
    Route::post('book-issues/issue', [BookIssueController::class, 'issue']);
    Route::post('book-issues/{issue}/return', [BookIssueController::class, 'returnBook']);
    Route::post('book-issues/{issue}/renew', [BookIssueController::class, 'renew']);
    Route::get('book-issues/{issue}/fine', [BookIssueController::class, 'calculateFine']);
    Route::apiResource('library-members', LibraryMemberController::class);
    Route::apiResource('library-fines', LibraryFineController::class);

    // Alumni
    Route::apiResource('alumni', AlumniController::class);
    Route::get('alumni/directory', [AlumniController::class, 'directory']);
    Route::post('alumni/{alumnus}/verify', [AlumniController::class, 'verify']);
    Route::apiResource('alumni-events', AlumniEventController::class);
    Route::post('alumni-events/{event}/send-invitation', [AlumniEventController::class, 'sendInvitation']);
    Route::post('alumni-events/{event}/track-attendance', [AlumniEventController::class, 'trackAttendance']);
    Route::apiResource('alumni-donations', AlumniDonationController::class);
    Route::apiResource('job-posts', JobPostController::class);

    // Hostel
    Route::apiResource('hostels', HostelController::class);
    Route::get('hostels/{hostel}/occupancy-report', [HostelController::class, 'getOccupancyReport']);
    Route::apiResource('hostel-rooms', HostelRoomController::class);
    Route::get('hostel-rooms/{room}/beds', [HostelRoomController::class, 'getBeds']);
    Route::apiResource('hostel-allocations', HostelAllocationController::class);
    Route::post('hostel-allocations/{allocation}/check-in', [HostelAllocationController::class, 'checkIn']);
    Route::post('hostel-allocations/{allocation}/check-out', [HostelAllocationController::class, 'checkOut']);
    Route::apiResource('hostel-visitors', HostelVisitorController::class);

    // Assets
    Route::apiResource('assets', AssetController::class);
    Route::post('assets/{asset}/allocate', [AssetController::class, 'allocate']);
    Route::post('assets/{asset}/maintain', [AssetController::class, 'maintain']);
    Route::post('assets/{asset}/depreciate', [AssetController::class, 'depreciate']);
    Route::post('assets/{asset}/audit', [AssetController::class, 'audit']);
    Route::apiResource('asset-categories', AssetCategoryController::class);
    Route::apiResource('asset-maintenance', AssetMaintenanceController::class);

    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::post('{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('mark-all-read', [NotificationController::class, 'markAllAsRead']);
        Route::get('unread-count', [NotificationController::class, 'getUnreadCount']);
    });

    // Settings
    Route::get('settings/school', [SettingsController::class, 'getSchoolSettings']);
    Route::put('settings/school', [SettingsController::class, 'updateSchoolSettings']);
    Route::get('settings/theme', [SettingsController::class, 'getTheme']);
    Route::put('settings/theme', [SettingsController::class, 'updateTheme']);
    Route::get('settings/language', [SettingsController::class, 'getLanguage']);
    Route::put('settings/language', [SettingsController::class, 'updateLanguage']);

    // Activity Logs
    Route::get('activity-logs', [ActivityLogController::class, 'index']);
    Route::get('activity-logs/by-user/{user}', [ActivityLogController::class, 'getByUser']);
    Route::get('activity-logs/by-module/{module}', [ActivityLogController::class, 'getByModule']);

    // Backups
    Route::prefix('backups')->group(function () {
        Route::get('/', [BackupController::class, 'list']);
        Route::post('create', [BackupController::class, 'create']);
        Route::get('download/{filename}', [BackupController::class, 'download']);
        Route::post('restore', [BackupController::class, 'restore']);
    });

    // Search
    Route::prefix('search')->group(function () {
        Route::get('global', [SearchController::class, 'globalSearch']);
        Route::get('students', [SearchController::class, 'searchStudents']);
        Route::get('teachers', [SearchController::class, 'searchTeachers']);
        Route::get('employees', [SearchController::class, 'searchEmployees']);
    });
});
