<?php

use App\Http\Controllers\Web\AcademicController;
use App\Http\Controllers\Web\AdmissionController;
use App\Http\Controllers\Web\AIController;
use App\Http\Controllers\Web\AlumniController;
use App\Http\Controllers\Web\AssignmentController;
use App\Http\Controllers\Web\LessonPlanController;
use App\Http\Controllers\Web\HomeworkController;
use App\Http\Controllers\Web\StudyMaterialController;
use App\Http\Controllers\Web\AttendanceController;
use App\Http\Controllers\Web\Auth\ForgotPasswordController;
use App\Http\Controllers\Web\Auth\LoginController;
use App\Http\Controllers\Web\Auth\RegisterController;
use App\Http\Controllers\Web\Auth\ResetPasswordController;
use App\Http\Controllers\Web\Auth\VerificationController;
use App\Http\Controllers\Web\BookController;
use App\Http\Controllers\Web\BookIssueController;
use App\Http\Controllers\Web\CertificateController;
use App\Http\Controllers\Web\ClassController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\DepartmentController;
use App\Http\Controllers\Web\DesignationController;
use App\Http\Controllers\Web\EmployeeController;
use App\Http\Controllers\Web\EventController;
use App\Http\Controllers\Web\AccountingController;
use App\Http\Controllers\Web\HealthController;
use App\Http\Controllers\Web\AssessmentController;
use App\Http\Controllers\Web\AssessmentManageController;
use App\Http\Controllers\Web\ExamController;
use App\Http\Controllers\Web\FeeController;
use App\Http\Controllers\Web\HolidayController;
use App\Http\Controllers\Web\HostelController;
use App\Http\Controllers\Web\InventoryController;
use App\Http\Controllers\Web\LeaveController;
use App\Http\Controllers\Web\LibraryController;
use App\Http\Controllers\Web\NotificationController;
use App\Http\Controllers\Web\PayrollController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\AssetController;
use App\Http\Controllers\Web\RecruitmentController;
use App\Http\Controllers\Web\HrController;
use App\Http\Controllers\Web\EmployeeLeaveController;
use App\Http\Controllers\Web\MisController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\SectionController;
use App\Http\Controllers\Web\SettingsController;
use App\Http\Controllers\Web\HomepageController;
use App\Http\Controllers\Web\MenuController;
use App\Http\Controllers\Web\StudentController;
use App\Http\Controllers\Web\StudentAwardController;
use App\Http\Controllers\Web\StudentDisciplineController;
use App\Http\Controllers\Web\StudentDocumentController;
use App\Http\Controllers\Web\StudentGroupController;
use App\Http\Controllers\Web\StudentHouseController;
use App\Http\Controllers\Web\StudentPromotionController;
use App\Http\Controllers\Web\StudentTransferController;
use App\Http\Controllers\Web\SubjectController;
use App\Http\Controllers\Web\RoomAllocationController;
use App\Http\Controllers\Web\SubstitutionController;
use App\Http\Controllers\Web\TimetableController;
use App\Http\Controllers\Web\TransportController;
use App\Http\Controllers\Web\FrontOfficeController;
use App\Http\Controllers\Web\CustomPageController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\RolePermissionController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('page/{slug}', [CustomPageController::class, 'show'])->name('custom-pages.show');
Route::post('application-form/submit', [AdmissionController::class, 'submitPublicApplicationForm'])->name('public.application-form.submit');
Route::get('application-form/submitted/{id}', [AdmissionController::class, 'submittedAction'])->name('public.application-form.submitted');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Email verification
Route::middleware('auth')->group(function () {
    Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->middleware('signed')->name('verification.verify');
    Route::post('email/resend', [VerificationController::class, 'resend'])->middleware('throttle:6,1')->name('verification.resend');
});

// Authenticated web routes
Route::middleware(['auth', 'verified', 'school', 'academic_year'])->group(function () {

    // Logout
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('permission:dashboard.view');

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('password', [ProfileController::class, 'changePassword'])->name('password');
        Route::post('avatar', [ProfileController::class, 'uploadAvatar'])->name('avatar');
    });

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('markAllRead');
    });

    // Student Portal (must be before students wildcard group)
    Route::get('students/portal', [StudentController::class, 'portal'])->name('students.portal')->middleware('permission:attendance.list');

    // Students
    Route::prefix('students')->name('students.')->middleware('permission:students.list|students.create|students.edit|students.delete')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('index')->middleware('permission:students.list');
        Route::get('create', [StudentController::class, 'create'])->name('create')->middleware('permission:students.create');
        Route::post('/', [StudentController::class, 'store'])->name('store')->middleware('permission:students.create');
        Route::get('{student}', [StudentController::class, 'show'])->name('show')->middleware('permission:students.list');
        Route::get('{student}/edit', [StudentController::class, 'edit'])->name('edit')->middleware('permission:students.edit');
        Route::put('{student}', [StudentController::class, 'update'])->name('update')->middleware('permission:students.edit');
        Route::delete('{student}', [StudentController::class, 'destroy'])->name('destroy')->middleware('permission:students.delete');
    });

    // Student Houses
    Route::prefix('student-houses')->name('student-houses.')->group(function () {
        Route::get('/', [StudentHouseController::class, 'index'])->name('index');
        Route::get('{studentHouse}', [StudentHouseController::class, 'show'])->name('show');
        Route::post('/', [StudentHouseController::class, 'store'])->name('store');
        Route::put('{studentHouse}', [StudentHouseController::class, 'update'])->name('update');
        Route::delete('{studentHouse}', [StudentHouseController::class, 'destroy'])->name('destroy');
    });

    // Student Groups
    Route::prefix('student-groups')->name('student-groups.')->group(function () {
        Route::get('/', [StudentGroupController::class, 'index'])->name('index');
        Route::get('{studentGroup}', [StudentGroupController::class, 'show'])->name('show');
        Route::post('/', [StudentGroupController::class, 'store'])->name('store');
        Route::put('{studentGroup}', [StudentGroupController::class, 'update'])->name('update');
        Route::delete('{studentGroup}', [StudentGroupController::class, 'destroy'])->name('destroy');
    });

    // Student Documents
    Route::prefix('student-documents')->name('student-documents.')->middleware('permission:documents.list')->group(function () {
        Route::get('/', [StudentDocumentController::class, 'index'])->name('index');
        Route::get('{studentDocument}', [StudentDocumentController::class, 'show'])->name('show');
        Route::post('/', [StudentDocumentController::class, 'store'])->name('store')->middleware('permission:documents.create');
        Route::delete('{studentDocument}', [StudentDocumentController::class, 'destroy'])->name('destroy')->middleware('permission:documents.delete');
    });

    // Student Disciplines
    Route::prefix('student-disciplines')->name('student-disciplines.')->group(function () {
        Route::get('/', [StudentDisciplineController::class, 'index'])->name('index');
        Route::get('{studentDiscipline}', [StudentDisciplineController::class, 'show'])->name('show');
        Route::post('/', [StudentDisciplineController::class, 'store'])->name('store');
        Route::put('{studentDiscipline}', [StudentDisciplineController::class, 'update'])->name('update');
        Route::delete('{studentDiscipline}', [StudentDisciplineController::class, 'destroy'])->name('destroy');
    });

    // Student Awards
    Route::prefix('student-awards')->name('student-awards.')->group(function () {
        Route::get('/', [StudentAwardController::class, 'index'])->name('index');
        Route::get('{studentAward}', [StudentAwardController::class, 'show'])->name('show');
        Route::post('/', [StudentAwardController::class, 'store'])->name('store');
        Route::put('{studentAward}', [StudentAwardController::class, 'update'])->name('update');
        Route::delete('{studentAward}', [StudentAwardController::class, 'destroy'])->name('destroy');
    });

    // Student Promotions
    Route::prefix('student-promotions')->name('student-promotions.')->group(function () {
        Route::get('/', [StudentPromotionController::class, 'index'])->name('index');
        Route::get('{studentPromotion}', [StudentPromotionController::class, 'show'])->name('show');
        Route::post('/', [StudentPromotionController::class, 'store'])->name('store');
        Route::delete('{studentPromotion}', [StudentPromotionController::class, 'destroy'])->name('destroy');
    });

    // Student Transfers
    Route::prefix('student-transfers')->name('student-transfers.')->group(function () {
        Route::get('/', [StudentTransferController::class, 'index'])->name('index');
        Route::get('{studentTransfer}', [StudentTransferController::class, 'show'])->name('show');
        Route::post('/', [StudentTransferController::class, 'store'])->name('store');
        Route::delete('{studentTransfer}', [StudentTransferController::class, 'destroy'])->name('destroy');
    });

    // Employees
    Route::prefix('employees')->name('employees.')->middleware('permission:staff.list|staff.create|staff.edit|staff.delete')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('index')->middleware('permission:staff.list');
        Route::get('create', [EmployeeController::class, 'create'])->name('create')->middleware('permission:staff.create');
        Route::post('/', [EmployeeController::class, 'store'])->name('store')->middleware('permission:staff.create');
        Route::get('{employee}', [EmployeeController::class, 'show'])->name('show')->middleware('permission:staff.list');
        Route::get('{employee}/edit', [EmployeeController::class, 'edit'])->name('edit')->middleware('permission:staff.edit');
        Route::put('{employee}', [EmployeeController::class, 'update'])->name('update')->middleware('permission:staff.edit');
        Route::delete('{employee}', [EmployeeController::class, 'destroy'])->name('destroy')->middleware('permission:staff.delete');
    });

    // Departments
    Route::prefix('departments')->name('departments.')->group(function () {
        Route::get('/', [DepartmentController::class, 'index'])->name('index');
        Route::get('create', [DepartmentController::class, 'create'])->name('create');
        Route::post('/', [DepartmentController::class, 'store'])->name('store');
        Route::get('{department}', [DepartmentController::class, 'show'])->name('show');
        Route::get('{department}/edit', [DepartmentController::class, 'edit'])->name('edit');
        Route::put('{department}', [DepartmentController::class, 'update'])->name('update');
        Route::delete('{department}', [DepartmentController::class, 'destroy'])->name('destroy');
    });

    // Designations
    Route::prefix('designations')->name('designations.')->group(function () {
        Route::get('/', [DesignationController::class, 'index'])->name('index');
        Route::get('create', [DesignationController::class, 'create'])->name('create');
        Route::post('/', [DesignationController::class, 'store'])->name('store');
        Route::get('{designation}', [DesignationController::class, 'show'])->name('show');
        Route::get('{designation}/edit', [DesignationController::class, 'edit'])->name('edit');
        Route::put('{designation}', [DesignationController::class, 'update'])->name('update');
        Route::delete('{designation}', [DesignationController::class, 'destroy'])->name('destroy');
    });

    // HR - Recruitment
    Route::prefix('hr')->name('hr.')->middleware('permission:staff.list|staff.create|staff.edit|staff.delete')->group(function () {
        Route::get('recruitment', [RecruitmentController::class, 'index'])->name('recruitment')->middleware('permission:staff.list');
        Route::get('recruitment/create', [RecruitmentController::class, 'create'])->name('recruitment.create')->middleware('permission:staff.create');
        Route::post('recruitment', [RecruitmentController::class, 'store'])->name('recruitment.store')->middleware('permission:staff.create');
        Route::get('recruitment/{id}', [RecruitmentController::class, 'show'])->name('recruitment.show')->middleware('permission:staff.list');
        Route::get('recruitment/{id}/edit', [RecruitmentController::class, 'edit'])->name('recruitment.edit')->middleware('permission:staff.edit');
        Route::put('recruitment/{id}', [RecruitmentController::class, 'update'])->name('recruitment.update')->middleware('permission:staff.edit');
        Route::delete('recruitment/{id}', [RecruitmentController::class, 'destroy'])->name('recruitment.destroy')->middleware('permission:staff.delete');

        // HR - Additional submenu pages
        Route::get('documents', [HrController::class, 'documents'])->name('documents')->middleware('permission:staff.list');
        Route::get('evaluations', [HrController::class, 'evaluations'])->name('evaluations')->middleware('permission:staff.list');
        Route::get('transfers', [HrController::class, 'transfers'])->name('transfers')->middleware('permission:staff.list');
        Route::get('promotions', [HrController::class, 'promotions'])->name('promotions')->middleware('permission:staff.list');
        Route::get('directory', [HrController::class, 'directory'])->name('directory')->middleware('permission:staff.list');
        Route::get('reports', [HrController::class, 'reports'])->name('reports')->middleware('permission:reports.list');

        // Employee Leave Management
        Route::prefix('employee-leave')->name('employee-leave.')->middleware('permission:attendance.list')->group(function () {
            Route::get('policies', [EmployeeLeaveController::class, 'policies'])->name('policies');
            Route::get('types', [EmployeeLeaveController::class, 'types'])->name('types');
            Route::get('requests', [EmployeeLeaveController::class, 'requests'])->name('requests');
            Route::get('approval-workflows', [EmployeeLeaveController::class, 'approvalWorkflows'])->name('approval-workflows');
            Route::get('balances', [EmployeeLeaveController::class, 'balances'])->name('balances');
            Route::get('encashments', [EmployeeLeaveController::class, 'encashments'])->name('encashments');
            Route::get('holiday-calendar', [EmployeeLeaveController::class, 'holidayCalendar'])->name('holiday-calendar');
            Route::post('holiday-calendar', [EmployeeLeaveController::class, 'storeHoliday'])->name('holiday-calendar.store');
            Route::put('holiday-calendar/{id}', [EmployeeLeaveController::class, 'updateHoliday'])->name('holiday-calendar.update');
            Route::delete('holiday-calendar/{id}', [EmployeeLeaveController::class, 'destroyHoliday'])->name('holiday-calendar.destroy');
            Route::get('reports', [EmployeeLeaveController::class, 'reports'])->name('reports');
        });
    });

    // Payroll
    Route::prefix('payroll')->name('payroll.')->middleware('permission:payroll.list|payroll.create|payroll.edit|payroll.delete')->group(function () {
        Route::get('salary-structures', [PayrollController::class, 'salaryStructures'])->name('index')->middleware('permission:payroll.list');
        Route::get('processing', [PayrollController::class, 'processing'])->name('processing')->middleware('permission:payroll.list');
        Route::get('loans', [PayrollController::class, 'loans'])->name('loans')->middleware('permission:payroll.list');
        Route::get('overtime', [PayrollController::class, 'overtime'])->name('overtime')->middleware('permission:payroll.list');
        Route::get('salary-components', [PayrollController::class, 'salaryComponents'])->name('salary-components')->middleware('permission:payroll.list');
        Route::get('tax-management', [PayrollController::class, 'taxManagement'])->name('tax-management')->middleware('permission:payroll.list');
        Route::get('bonus-management', [PayrollController::class, 'bonusManagement'])->name('bonus-management')->middleware('permission:payroll.list');
        Route::get('reports', [PayrollController::class, 'payrollReports'])->name('reports')->middleware('permission:reports.list');
        Route::get('tally-export', [PayrollController::class, 'tallyExport'])->name('tally-export')->middleware('permission:payroll.list');
    });

    // Academic
    Route::prefix('academic')->name('academic.')->middleware('permission:classes.list|subjects.list|academic_years.list|assignments.list|homework.list')->group(function () {
        Route::get('/', [AcademicController::class, 'index'])->name('index');
        Route::get('classes', [AcademicController::class, 'classes'])->name('classes')->middleware('permission:classes.list');
        Route::post('classes', [AcademicController::class, 'storeClass'])->name('classes.store')->middleware('permission:classes.create');
        Route::put('classes/{id}', [AcademicController::class, 'updateClass'])->name('classes.update')->middleware('permission:classes.edit');
        Route::delete('classes/{id}', [AcademicController::class, 'destroyClass'])->name('classes.destroy')->middleware('permission:classes.delete');
        Route::get('sections', [AcademicController::class, 'sections'])->name('sections')->middleware('permission:sections.list');
        Route::post('sections', [AcademicController::class, 'storeSection'])->name('sections.store')->middleware('permission:sections.create');
        Route::put('sections/{id}', [AcademicController::class, 'updateSection'])->name('sections.update')->middleware('permission:sections.edit');
        Route::delete('sections/{id}', [AcademicController::class, 'destroySection'])->name('sections.destroy')->middleware('permission:sections.delete');
        Route::get('subjects', [AcademicController::class, 'subjects'])->name('subjects')->middleware('permission:subjects.list');
        Route::post('subjects', [AcademicController::class, 'storeSubject'])->name('subjects.store')->middleware('permission:subjects.create');
        Route::put('subjects/{id}', [AcademicController::class, 'updateSubject'])->name('subjects.update')->middleware('permission:subjects.edit');
        Route::delete('subjects/{id}', [AcademicController::class, 'destroySubject'])->name('subjects.destroy')->middleware('permission:subjects.delete');
        Route::get('academic-years', [AcademicController::class, 'academicYears'])->name('academic-years')->middleware('permission:academic_years.list');
        Route::post('academic-years', [AcademicController::class, 'storeAcademicYear'])->name('academic-years.store')->middleware('permission:academic_years.create');
        Route::delete('academic-years/{id}', [AcademicController::class, 'destroyAcademicYear'])->name('academic-years.destroy')->middleware('permission:academic_years.delete');
        Route::get('curriculum', [AcademicController::class, 'curriculum'])->name('curriculum');
        Route::post('curriculum', [AcademicController::class, 'storeCurriculum'])->name('curriculum.store');
        Route::put('curriculum/{id}', [AcademicController::class, 'updateCurriculum'])->name('curriculum.update');
        Route::delete('curriculum/{id}', [AcademicController::class, 'destroyCurriculum'])->name('curriculum.destroy');
        Route::get('lesson-plans', [AcademicController::class, 'lessonPlans'])->name('lesson-plans');
        Route::post('lesson-plans', [AcademicController::class, 'storeLessonPlan'])->name('lesson-plans.store');
        Route::put('lesson-plans/{id}', [AcademicController::class, 'updateLessonPlan'])->name('lesson-plans.update');
        Route::delete('lesson-plans/{id}', [AcademicController::class, 'destroyLessonPlan'])->name('lesson-plans.destroy');
        Route::get('assignments', [AcademicController::class, 'assignments'])->name('assignments')->middleware('permission:assignments.list');
        Route::post('assignments', [AcademicController::class, 'storeAssignment'])->name('assignments.store')->middleware('permission:assignments.create');
        Route::put('assignments/{id}', [AcademicController::class, 'updateAssignment'])->name('assignments.update')->middleware('permission:assignments.edit');
        Route::delete('assignments/{id}', [AcademicController::class, 'destroyAssignment'])->name('assignments.destroy')->middleware('permission:assignments.delete');
        Route::get('homework', [AcademicController::class, 'homework'])->name('homework')->middleware('permission:homework.list');
        Route::post('homework', [AcademicController::class, 'storeHomework'])->name('homework.store')->middleware('permission:homework.create');
        Route::put('homework/{id}', [AcademicController::class, 'updateHomework'])->name('homework.update')->middleware('permission:homework.edit');
        Route::delete('homework/{id}', [AcademicController::class, 'destroyHomework'])->name('homework.destroy')->middleware('permission:homework.delete');
        Route::get('study-materials', [AcademicController::class, 'studyMaterials'])->name('study-materials');
        Route::post('study-materials', [AcademicController::class, 'storeStudyMaterial'])->name('study-materials.store');
        Route::put('study-materials/{id}', [AcademicController::class, 'updateStudyMaterial'])->name('study-materials.update');
        Route::delete('study-materials/{id}', [AcademicController::class, 'destroyStudyMaterial'])->name('study-materials.destroy');
        Route::get('teacher-diary', [AcademicController::class, 'teacherDiary'])->name('teacher-diary');
        Route::post('teacher-diary', [AcademicController::class, 'storeDiary'])->name('teacher-diary.store');
        Route::put('teacher-diary/{id}', [AcademicController::class, 'updateDiary'])->name('teacher-diary.update');
        Route::delete('teacher-diary/{id}', [AcademicController::class, 'destroyDiary'])->name('teacher-diary.destroy');
        Route::get('reports', [AcademicController::class, 'reports'])->name('reports');
    });

    // Classes
    Route::prefix('classes')->name('classes.')->middleware('permission:classes.list|classes.create|classes.edit|classes.delete')->group(function () {
        Route::get('/', [ClassController::class, 'index'])->name('index')->middleware('permission:classes.list');
        Route::get('create', [ClassController::class, 'create'])->name('create')->middleware('permission:classes.create');
        Route::post('/', [ClassController::class, 'store'])->name('store')->middleware('permission:classes.create');
        Route::get('{class}', [ClassController::class, 'show'])->name('show')->middleware('permission:classes.list');
        Route::get('{class}/edit', [ClassController::class, 'edit'])->name('edit')->middleware('permission:classes.edit');
        Route::put('{class}', [ClassController::class, 'update'])->name('update')->middleware('permission:classes.edit');
        Route::delete('{class}', [ClassController::class, 'destroy'])->name('destroy')->middleware('permission:classes.delete');
        Route::get('{class}/sections', [ClassController::class, 'sections'])->name('sections')->middleware('permission:sections.list');
    });

    // Sections
    Route::prefix('sections')->name('sections.')->middleware('permission:sections.list|sections.create|sections.edit|sections.delete')->group(function () {
        Route::get('/', [SectionController::class, 'index'])->name('index')->middleware('permission:sections.list');
        Route::get('create', [SectionController::class, 'create'])->name('create')->middleware('permission:sections.create');
        Route::post('/', [SectionController::class, 'store'])->name('store')->middleware('permission:sections.create');
        Route::get('{section}', [SectionController::class, 'show'])->name('show')->middleware('permission:sections.list');
        Route::get('{section}/edit', [SectionController::class, 'edit'])->name('edit')->middleware('permission:sections.edit');
        Route::put('{section}', [SectionController::class, 'update'])->name('update')->middleware('permission:sections.edit');
        Route::delete('{section}', [SectionController::class, 'destroy'])->name('destroy')->middleware('permission:sections.delete');
    });

    // Subjects
    Route::prefix('subjects')->name('subjects.')->middleware('permission:subjects.list|subjects.create|subjects.edit|subjects.delete')->group(function () {
        Route::get('/', [SubjectController::class, 'index'])->name('index')->middleware('permission:subjects.list');
        Route::get('create', [SubjectController::class, 'create'])->name('create')->middleware('permission:subjects.create');
        Route::post('/', [SubjectController::class, 'store'])->name('store')->middleware('permission:subjects.create');
        Route::get('{subject}', [SubjectController::class, 'show'])->name('show')->middleware('permission:subjects.list');
        Route::get('{subject}/edit', [SubjectController::class, 'edit'])->name('edit')->middleware('permission:subjects.edit');
        Route::put('{subject}', [SubjectController::class, 'update'])->name('update')->middleware('permission:subjects.edit');
        Route::delete('{subject}', [SubjectController::class, 'destroy'])->name('destroy')->middleware('permission:subjects.delete');
    });

    // Academic - Lesson Plans
    Route::prefix('lesson-plans')->name('lesson-plans.')->group(function () {
        Route::get('/', [LessonPlanController::class, 'index'])->name('index');
        Route::get('create', [LessonPlanController::class, 'create'])->name('create');
        Route::post('/', [LessonPlanController::class, 'store'])->name('store');
        Route::get('{lessonPlan}', [LessonPlanController::class, 'show'])->name('show');
        Route::get('{lessonPlan}/edit', [LessonPlanController::class, 'edit'])->name('edit');
        Route::put('{lessonPlan}', [LessonPlanController::class, 'update'])->name('update');
        Route::delete('{lessonPlan}', [LessonPlanController::class, 'destroy'])->name('destroy');
    });

    // Academic - Assignments
    Route::prefix('assignments')->name('assignments.')->middleware('permission:assignments.list|assignments.create|assignments.edit|assignments.delete')->group(function () {
        Route::get('/', [AssignmentController::class, 'index'])->name('index')->middleware('permission:assignments.list');
        Route::get('create', [AssignmentController::class, 'create'])->name('create')->middleware('permission:assignments.create');
        Route::post('/', [AssignmentController::class, 'store'])->name('store')->middleware('permission:assignments.create');
        Route::get('{assignment}', [AssignmentController::class, 'show'])->name('show')->middleware('permission:assignments.list');
        Route::get('{assignment}/edit', [AssignmentController::class, 'edit'])->name('edit')->middleware('permission:assignments.edit');
        Route::put('{assignment}', [AssignmentController::class, 'update'])->name('update')->middleware('permission:assignments.edit');
        Route::delete('{assignment}', [AssignmentController::class, 'destroy'])->name('destroy')->middleware('permission:assignments.delete');
    });

    // Academic - Homework
    Route::prefix('homework')->name('homework.')->middleware('permission:homework.list|homework.create|homework.edit|homework.delete')->group(function () {
        Route::get('/', [HomeworkController::class, 'index'])->name('index')->middleware('permission:homework.list');
        Route::get('create', [HomeworkController::class, 'create'])->name('create')->middleware('permission:homework.create');
        Route::post('/', [HomeworkController::class, 'store'])->name('store')->middleware('permission:homework.create');
        Route::get('{homework}', [HomeworkController::class, 'show'])->name('show')->middleware('permission:homework.list');
        Route::get('{homework}/edit', [HomeworkController::class, 'edit'])->name('edit')->middleware('permission:homework.edit');
        Route::put('{homework}', [HomeworkController::class, 'update'])->name('update')->middleware('permission:homework.edit');
        Route::delete('{homework}', [HomeworkController::class, 'destroy'])->name('destroy')->middleware('permission:homework.delete');
    });

    // Academic - Study Materials
    Route::prefix('study-materials')->name('study-materials.')->group(function () {
        Route::get('/', [StudyMaterialController::class, 'index'])->name('index');
        Route::get('create', [StudyMaterialController::class, 'create'])->name('create');
        Route::post('/', [StudyMaterialController::class, 'store'])->name('store');
        Route::get('{studyMaterial}', [StudyMaterialController::class, 'show'])->name('show');
        Route::get('{studyMaterial}/edit', [StudyMaterialController::class, 'edit'])->name('edit');
        Route::put('{studyMaterial}', [StudyMaterialController::class, 'update'])->name('update');
        Route::delete('{studyMaterial}', [StudyMaterialController::class, 'destroy'])->name('destroy');
    });

    // Attendance
    Route::prefix('attendance')->name('attendance.')->middleware('permission:attendance.list|attendance.create|attendance.edit|attendance.delete')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index')->middleware('permission:attendance.list');
        Route::get('create', [AttendanceController::class, 'create'])->name('create')->middleware('permission:attendance.create');
        Route::post('/', [AttendanceController::class, 'store'])->name('store')->middleware('permission:attendance.create');
        Route::get('mark', [AttendanceController::class, 'mark'])->name('mark')->middleware('permission:attendance.create');

        // New submenu pages
        Route::get('daily', [AttendanceController::class, 'daily'])->name('daily')->middleware('permission:attendance.list');
        Route::get('period', [AttendanceController::class, 'period'])->name('period')->middleware('permission:attendance.list');
        Route::get('subject', [AttendanceController::class, 'subject'])->name('subject')->middleware('permission:attendance.list');
        Route::get('rfid', [AttendanceController::class, 'rfid'])->name('rfid')->middleware('permission:attendance.list');
        Route::get('uhf', [AttendanceController::class, 'uhf'])->name('uhf')->middleware('permission:attendance.list');
        Route::get('biometric', [AttendanceController::class, 'biometric'])->name('biometric')->middleware('permission:attendance.list');
        Route::get('face-recognition', [AttendanceController::class, 'faceRecognition'])->name('face-recognition')->middleware('permission:attendance.list');
        Route::get('correction', [AttendanceController::class, 'correction'])->name('correction')->middleware('permission:attendance.list');
        Route::get('late-entry', [AttendanceController::class, 'lateEntry'])->name('late-entry')->middleware('permission:attendance.list');
        Route::get('leave-tracking', [AttendanceController::class, 'leaveTracking'])->name('leave-tracking')->middleware('permission:attendance.list');
        Route::get('parent-notification', [AttendanceController::class, 'parentNotification'])->name('parent-notification')->middleware('permission:attendance.list');
        Route::get('analytics', [AttendanceController::class, 'analytics'])->name('analytics')->middleware('permission:attendance.list');
        Route::get('reports', [AttendanceController::class, 'reports'])->name('reports')->middleware('permission:reports.list');

        // Wildcard must be last to avoid catching named routes
        Route::get('{attendance}', [AttendanceController::class, 'show'])->name('show')->middleware('permission:attendance.list');
    });

    // Leaves
    Route::prefix('leaves')->name('leaves.')->group(function () {
        Route::get('/', [LeaveController::class, 'index'])->name('index')->middleware('permission:attendance.list');
        Route::get('create', [LeaveController::class, 'create'])->name('create')->middleware('permission:attendance.create');
        Route::post('/', [LeaveController::class, 'store'])->name('store')->middleware('permission:attendance.create');
        Route::get('{leave}', [LeaveController::class, 'show'])->name('show')->middleware('permission:attendance.list');
        Route::post('{leave}/approve', [LeaveController::class, 'approve'])->name('approve')->middleware('permission:attendance.edit');
        Route::post('{leave}/reject', [LeaveController::class, 'reject'])->name('reject')->middleware('permission:attendance.edit');
    });

    // Holidays
    Route::prefix('holidays')->name('holidays.')->middleware('permission:holidays.list|holidays.create|holidays.edit|holidays.delete')->group(function () {
        Route::get('/', [HolidayController::class, 'index'])->name('index')->middleware('permission:holidays.list');
        Route::get('create', [HolidayController::class, 'create'])->name('create')->middleware('permission:holidays.create');
        Route::post('/', [HolidayController::class, 'store'])->name('store')->middleware('permission:holidays.create');
        Route::get('{holiday}', [HolidayController::class, 'show'])->name('show')->middleware('permission:holidays.list');
        Route::get('{holiday}/edit', [HolidayController::class, 'edit'])->name('edit')->middleware('permission:holidays.edit');
        Route::put('{holiday}', [HolidayController::class, 'update'])->name('update')->middleware('permission:holidays.edit');
        Route::delete('{holiday}', [HolidayController::class, 'destroy'])->name('destroy')->middleware('permission:holidays.delete');
    });

    // Fees
    Route::prefix('fees')->name('fees.')->middleware('permission:fees.list|fees.create|fees.edit|fees.delete')->group(function () {
        Route::get('/', [FeeController::class, 'feeStructure'])->name('index')->middleware('permission:fees.list');
        Route::get('create', [FeeController::class, 'create'])->name('create')->middleware('permission:fees.create');
        Route::post('/', [FeeController::class, 'store'])->name('store')->middleware('permission:fees.create');

        // New submenu pages
        Route::get('fee-structure', [FeeController::class, 'feeStructure'])->name('fee-structure')->middleware('permission:fees.list');
        Route::get('fee-categories', [FeeController::class, 'feeCategories'])->name('fee-categories')->middleware('permission:fees.list');
        Route::get('installment-plans', [FeeController::class, 'installmentPlans'])->name('installment-plans')->middleware('permission:fees.list');
        Route::get('scholarship-management', [FeeController::class, 'scholarshipManagement'])->name('scholarship-management')->middleware('permission:fees.list');
        Route::get('discount-management', [FeeController::class, 'discountManagement'])->name('discount-management')->middleware('permission:fees.list');
        Route::get('fine-management', [FeeController::class, 'fineManagement'])->name('fine-management')->middleware('permission:fees.list');
        Route::get('fee-collection', [FeeController::class, 'feeCollection'])->name('fee-collection')->middleware('permission:fees.list');
        Route::get('online-payment', [FeeController::class, 'onlinePayment'])->name('online-payment')->middleware('permission:fees.list');
        Route::get('receipt-generation', [FeeController::class, 'receiptGeneration'])->name('receipt-generation')->middleware('permission:fees.list');
        Route::get('due-tracking', [FeeController::class, 'dueTracking'])->name('due-tracking')->middleware('permission:fees.list');
        Route::get('auto-reminder', [FeeController::class, 'autoReminder'])->name('auto-reminder')->middleware('permission:fees.list');
        Route::get('payment-reconciliation', [FeeController::class, 'paymentReconciliation'])->name('payment-reconciliation')->middleware('permission:fees.list');
        Route::get('financial-reports', [FeeController::class, 'financialReports'])->name('financial-reports')->middleware('permission:reports.list');

        // Print receipt
        Route::get('print-receipt/{id}', [FeeController::class, 'printReceipt'])->name('print-receipt')->middleware('permission:fees.list');

        // Wildcard must be last
        Route::get('{fee}', [FeeController::class, 'show'])->name('show')->middleware('permission:fees.list');
        Route::delete('{fee}', [FeeController::class, 'destroy'])->name('destroy')->middleware('permission:fees.delete');
    });

    // Admissions
    Route::prefix('admissions')->name('admissions.')->middleware('permission:admissions.list|admissions.create|admissions.edit|admissions.delete')->group(function () {
        Route::get('/', [AdmissionController::class, 'index'])->name('index')->middleware('permission:admissions.list');
        Route::post('store', [AdmissionController::class, 'store'])->name('store')->middleware('permission:admissions.create');
        Route::put('update/{id}', [AdmissionController::class, 'update'])->name('update')->middleware('permission:admissions.edit');
        Route::delete('destroy/{id}', [AdmissionController::class, 'destroy'])->name('destroy')->middleware('permission:admissions.delete');
        Route::post('approve/{id}', [AdmissionController::class, 'approve'])->name('approve')->middleware('permission:admissions.edit');
        Route::post('reject/{id}', [AdmissionController::class, 'reject'])->name('reject')->middleware('permission:admissions.edit');
        Route::post('enquiries/store', [AdmissionController::class, 'storeEnquiry'])->name('enquiries.store')->middleware('permission:admissions.create');
        Route::put('enquiries/update/{id}', [AdmissionController::class, 'updateEnquiry'])->name('enquiries.update')->middleware('permission:admissions.edit');
        Route::delete('enquiries/destroy/{id}', [AdmissionController::class, 'destroyEnquiry'])->name('enquiries.destroy')->middleware('permission:admissions.delete');
        Route::get('entrance-exam', [AdmissionController::class, 'entranceExam'])->name('entrance-exam')->middleware('permission:admissions.list');
        Route::post('entrance-exam/store', [AdmissionController::class, 'storeEntranceExam'])->name('entrance-exam.store')->middleware('permission:admissions.create');
        Route::put('entrance-exam/update/{id}', [AdmissionController::class, 'updateEntranceExam'])->name('entrance-exam.update')->middleware('permission:admissions.edit');
        Route::delete('entrance-exam/destroy/{id}', [AdmissionController::class, 'destroyEntranceExam'])->name('entrance-exam.destroy')->middleware('permission:admissions.delete');
        Route::post('exam-results/store', [AdmissionController::class, 'storeExamResult'])->name('exam-results.store')->middleware('permission:admissions.create');
        Route::get('merit-list', [AdmissionController::class, 'meritList'])->name('merit-list')->middleware('permission:admissions.list');
        Route::post('merit-list/store', [AdmissionController::class, 'storeMeritList'])->name('merit-list.store')->middleware('permission:admissions.create');
        Route::put('merit-list/update/{id}', [AdmissionController::class, 'updateMeritList'])->name('merit-list.update')->middleware('permission:admissions.edit');
        Route::delete('merit-list/destroy/{id}', [AdmissionController::class, 'destroyMeritList'])->name('merit-list.destroy')->middleware('permission:admissions.delete');
        Route::get('waiting-list', [AdmissionController::class, 'waitingList'])->name('waiting-list')->middleware('permission:admissions.list');
        Route::post('waiting-list/store', [AdmissionController::class, 'storeWaitingList'])->name('waiting-list.store')->middleware('permission:admissions.create');
        Route::put('waiting-list/update/{id}', [AdmissionController::class, 'updateWaitingList'])->name('waiting-list.update')->middleware('permission:admissions.edit');
        Route::delete('waiting-list/destroy/{id}', [AdmissionController::class, 'destroyWaitingList'])->name('waiting-list.destroy')->middleware('permission:admissions.delete');

        // New 16 submenus
        Route::get('online-portal', [AdmissionController::class, 'onlinePortal'])->name('online-portal')->middleware('permission:admissions.list');
        Route::post('online-portal/submit', [AdmissionController::class, 'submitOnlinePortal'])->name('online-portal.submit')->middleware('permission:admissions.create');
            Route::get('application-forms', [AdmissionController::class, 'applicationForms'])->name('application-forms')->middleware('permission:admissions.list');
            Route::post('application-forms/store', [AdmissionController::class, 'storeApplicationForm'])->name('application-forms.store')->middleware('permission:admissions.create');
            Route::put('application-forms/update/{id}', [AdmissionController::class, 'updateApplicationForm'])->name('application-forms.update')->middleware('permission:admissions.edit');
            Route::delete('application-forms/destroy/{id}', [AdmissionController::class, 'destroyApplicationForm'])->name('application-forms.destroy')->middleware('permission:admissions.delete');
            Route::get('application-forms/print/{id}', [AdmissionController::class, 'printApplicationForm'])->name('application-forms.print')->middleware('permission:admissions.list');
        Route::get('admission-enquiry', [AdmissionController::class, 'admissionEnquiry'])->name('admission-enquiry')->middleware('permission:admissions.list');
        Route::get('lead-management', [AdmissionController::class, 'leadManagement'])->name('lead-management')->middleware('permission:admissions.list');
        Route::post('lead-management/convert/{id}', [AdmissionController::class, 'convertLead'])->name('lead-management.convert')->middleware('permission:admissions.edit');
        Route::get('student-registration', [AdmissionController::class, 'studentRegistration'])->name('student-registration')->middleware('permission:admissions.list');
        Route::post('student-registration/store', [AdmissionController::class, 'storeStudentRegistration'])->name('student-registration.store')->middleware('permission:admissions.create');
        Route::get('document-upload', [AdmissionController::class, 'documentUpload'])->name('document-upload')->middleware('permission:admissions.list');
        Route::post('document-upload/store', [AdmissionController::class, 'storeDocument'])->name('document-upload.store')->middleware('permission:admissions.create');
        Route::delete('document-upload/delete/{id}', [AdmissionController::class, 'deleteDocument'])->name('document-upload.delete')->middleware('permission:admissions.delete');
        Route::get('admission-workflow', [AdmissionController::class, 'admissionWorkflow'])->name('admission-workflow')->middleware('permission:admissions.list');
        Route::get('admission-approval', [AdmissionController::class, 'admissionApproval'])->name('admission-approval')->middleware('permission:admissions.list');
        Route::post('admission-approval/bulk-approve', [AdmissionController::class, 'bulkApprove'])->name('admission-approval.bulk-approve')->middleware('permission:admissions.edit');
        Route::post('admission-approval/bulk-reject', [AdmissionController::class, 'bulkReject'])->name('admission-approval.bulk-reject')->middleware('permission:admissions.edit');
        Route::get('merit-list-generation', [AdmissionController::class, 'meritListGeneration'])->name('merit-list-generation')->middleware('permission:admissions.list');
        Route::post('merit-list-generation/generate', [AdmissionController::class, 'generateMeritList'])->name('merit-list-generation.generate')->middleware('permission:admissions.create');
        Route::get('interview-scheduling', [AdmissionController::class, 'interviewScheduling'])->name('interview-scheduling')->middleware('permission:admissions.list');
        Route::post('interview-scheduling/schedule', [AdmissionController::class, 'scheduleInterview'])->name('interview-scheduling.schedule')->middleware('permission:admissions.create');
        Route::get('admission-fee-collection', [AdmissionController::class, 'admissionFeeCollection'])->name('admission-fee-collection')->middleware('permission:admissions.list');
        Route::post('admission-fee-collection/store', [AdmissionController::class, 'storeAdmissionFee'])->name('admission-fee-collection.store')->middleware('permission:admissions.create');
        Route::get('student-id-generation', [AdmissionController::class, 'studentIdGeneration'])->name('student-id-generation')->middleware('permission:admissions.list');
        Route::post('student-id-generation/generate/{id}', [AdmissionController::class, 'generateStudentId'])->name('student-id-generation.generate')->middleware('permission:admissions.create');
        Route::get('parent-registration', [AdmissionController::class, 'parentRegistration'])->name('parent-registration')->middleware('permission:admissions.list');
        Route::post('parent-registration/store', [AdmissionController::class, 'storeParent'])->name('parent-registration.store')->middleware('permission:admissions.create');
        Route::put('parent-registration/update/{id}', [AdmissionController::class, 'updateParent'])->name('parent-registration.update')->middleware('permission:admissions.edit');
        Route::delete('parent-registration/destroy/{id}', [AdmissionController::class, 'destroyParent'])->name('parent-registration.destroy')->middleware('permission:admissions.delete');
        Route::get('admission-reports', [AdmissionController::class, 'admissionReports'])->name('admission-reports')->middleware('permission:reports.list');
    });

    // Exams
    Route::prefix('exams')->name('exams.')->middleware('permission:exams.list|exams.create|exams.edit|exams.delete')->group(function () {
        Route::get('/', [ExamController::class, 'index'])->name('index')->middleware('permission:exams.list');
        Route::get('create', [ExamController::class, 'create'])->name('create')->middleware('permission:exams.create');
        Route::post('/', [ExamController::class, 'store'])->name('store')->middleware('permission:exams.create');
        Route::get('{exam}', [ExamController::class, 'show'])->name('show')->middleware('permission:exams.list');
        Route::get('{exam}/edit', [ExamController::class, 'edit'])->name('edit')->middleware('permission:exams.edit');
        Route::put('{exam}', [ExamController::class, 'update'])->name('update')->middleware('permission:exams.edit');
        Route::delete('{exam}', [ExamController::class, 'destroy'])->name('destroy')->middleware('permission:exams.delete');
        Route::get('{exam}/schedule', [ExamController::class, 'schedule'])->name('schedule')->middleware('permission:exams.list');
        Route::get('{exam}/results', [ExamController::class, 'results'])->name('results')->middleware('permission:grades.list');
    });

    // Assessments
    Route::prefix('assessment')->name('assessment.')->middleware('permission:grades.list|grades.create|grades.edit|grades.delete')->group(function () {
        Route::get('/', [AssessmentController::class, 'index'])->name('index')->middleware('permission:grades.list');
        Route::get('results', [AssessmentController::class, 'results'])->name('results')->middleware('permission:grades.list');
        Route::get('grading', [AssessmentController::class, 'grading'])->name('grading')->middleware('permission:grades.list');
        Route::get('continuous', [AssessmentController::class, 'continuous'])->name('continuous')->middleware('permission:grades.list');
        Route::get('create', [AssessmentController::class, 'create'])->name('create')->middleware('permission:grades.create');
        Route::post('/', [AssessmentController::class, 'store'])->name('store')->middleware('permission:grades.create');

        // Assessment management submenus (before wildcards)
        Route::get('exam-setup', [AssessmentManageController::class, 'examSetup'])->name('exam-setup')->middleware('permission:grades.list');
        Route::get('grading-system', [AssessmentManageController::class, 'gradingSystem'])->name('grading-system')->middleware('permission:grades.list');
        Route::get('subject-marks', [AssessmentManageController::class, 'subjectMarks'])->name('subject-marks')->middleware('permission:grades.list');
        Route::get('practical-marks', [AssessmentManageController::class, 'practicalMarks'])->name('practical-marks')->middleware('permission:grades.list');
        Route::get('assignment-marks', [AssessmentManageController::class, 'assignmentMarks'])->name('assignment-marks')->middleware('permission:grades.list');
        Route::get('continuous-assessment', [AssessmentManageController::class, 'continuousAssessment'])->name('continuous-assessment')->middleware('permission:grades.list');
        Route::get('online-examination', [AssessmentManageController::class, 'onlineExamination'])->name('online-examination')->middleware('permission:grades.list');
        Route::get('ai-evaluation', [AssessmentManageController::class, 'aiEvaluation'])->name('ai-evaluation')->middleware('permission:grades.list');
        Route::get('result-processing', [AssessmentManageController::class, 'resultProcessing'])->name('result-processing')->middleware('permission:grades.list');
        Route::get('ranking', [AssessmentManageController::class, 'ranking'])->name('ranking')->middleware('permission:grades.list');
        Route::get('performance-analytics', [AssessmentManageController::class, 'performanceAnalytics'])->name('performance-analytics')->middleware('permission:grades.list');

        // Wildcard must be last
        Route::get('{id}', [AssessmentController::class, 'show'])->name('show')->middleware('permission:grades.list');
        Route::get('{id}/edit', [AssessmentController::class, 'edit'])->name('edit')->middleware('permission:grades.edit');
        Route::put('{id}', [AssessmentController::class, 'update'])->name('update')->middleware('permission:grades.edit');
        Route::delete('{id}', [AssessmentController::class, 'destroy'])->name('destroy')->middleware('permission:grades.delete');
    });

    // Timetables
    Route::prefix('timetables')->name('timetables.')->middleware('permission:timetable.list|timetable.create|timetable.edit|timetable.delete')->group(function () {
        Route::get('/', [TimetableController::class, 'index'])->name('index')->middleware('permission:timetable.list');
        Route::get('create', [TimetableController::class, 'create'])->name('create')->middleware('permission:timetable.create');
        Route::post('/', [TimetableController::class, 'store'])->name('store')->middleware('permission:timetable.create');

        // New submenu pages
        Route::get('class-scheduling', [TimetableController::class, 'classScheduling'])->name('class-scheduling')->middleware('permission:timetable.list');
        Route::get('teacher-allocation', [TimetableController::class, 'teacherAllocation'])->name('teacher-allocation')->middleware('permission:timetable.list');
        Route::get('subject-allocation', [TimetableController::class, 'subjectAllocation'])->name('subject-allocation')->middleware('permission:timetable.list');
        Route::get('room-allocation', [TimetableController::class, 'roomAllocation'])->name('room-allocation')->middleware('permission:timetable.list');
        Route::get('conflict-detection', [TimetableController::class, 'conflictDetection'])->name('conflict-detection')->middleware('permission:timetable.list');
        Route::get('ai-generator', [TimetableController::class, 'aiGenerator'])->name('ai-generator')->middleware('permission:timetable.list');
        Route::get('exam-timetable', [TimetableController::class, 'examTimetable'])->name('exam-timetable')->middleware('permission:timetable.list');
        Route::get('timetable-reports', [TimetableController::class, 'timetableReports'])->name('timetable-reports')->middleware('permission:reports.list');

        // Wildcard must be last
        Route::get('{timetable}', [TimetableController::class, 'show'])->name('show')->middleware('permission:timetable.list');
        Route::get('{timetable}/edit', [TimetableController::class, 'edit'])->name('edit')->middleware('permission:timetable.edit');
        Route::put('{timetable}', [TimetableController::class, 'update'])->name('update')->middleware('permission:timetable.edit');
        Route::delete('{timetable}', [TimetableController::class, 'destroy'])->name('destroy')->middleware('permission:timetable.delete');
    });

    // Room Allocations
    Route::prefix('timetable')->name('timetable.')->middleware('permission:timetable.list|timetable.create|timetable.edit|timetable.delete')->group(function () {
        Route::get('room-allocation', [RoomAllocationController::class, 'index'])->name('room-allocation')->middleware('permission:timetable.list');
        Route::get('room-allocation/create', [RoomAllocationController::class, 'create'])->name('room-allocation.create')->middleware('permission:timetable.create');
        Route::post('room-allocation', [RoomAllocationController::class, 'store'])->name('room-allocation.store')->middleware('permission:timetable.create');
        Route::get('room-allocation/{room}', [RoomAllocationController::class, 'show'])->name('room-allocation.show')->middleware('permission:timetable.list');
        Route::get('room-allocation/{room}/edit', [RoomAllocationController::class, 'edit'])->name('room-allocation.edit')->middleware('permission:timetable.edit');
        Route::put('room-allocation/{room}', [RoomAllocationController::class, 'update'])->name('room-allocation.update')->middleware('permission:timetable.edit');
        Route::delete('room-allocation/{room}', [RoomAllocationController::class, 'destroy'])->name('room-allocation.destroy')->middleware('permission:timetable.delete');

        Route::get('substitutions', [SubstitutionController::class, 'index'])->name('substitutions')->middleware('permission:timetable.list');
        Route::get('substitutions/create', [SubstitutionController::class, 'create'])->name('substitutions.create')->middleware('permission:timetable.create');
        Route::post('substitutions', [SubstitutionController::class, 'store'])->name('substitutions.store')->middleware('permission:timetable.create');
        Route::get('substitutions/{substitution}', [SubstitutionController::class, 'show'])->name('substitutions.show')->middleware('permission:timetable.list');
        Route::post('substitutions/{substitution}/approve', [SubstitutionController::class, 'approve'])->name('substitutions.approve')->middleware('permission:timetable.edit');
        Route::post('substitutions/{substitution}/reject', [SubstitutionController::class, 'reject'])->name('substitutions.reject')->middleware('permission:timetable.edit');
    });

    // Certificates
    Route::prefix('certificates')->name('certificates.')->middleware('permission:students.list|students.create')->group(function () {
        Route::get('transfer', [CertificateController::class, 'transfer'])->name('transfer');
        Route::get('character', [CertificateController::class, 'character'])->name('character');
        Route::get('bonafide', [CertificateController::class, 'bonafide'])->name('bonafide');
        Route::get('course-completion', [CertificateController::class, 'courseCompletion'])->name('course-completion');
        Route::get('custom', [CertificateController::class, 'customCertificates'])->name('custom');
        Route::post('store', [CertificateController::class, 'store'])->name('store');
        Route::put('{id}', [CertificateController::class, 'update'])->name('update');
        Route::delete('{id}', [CertificateController::class, 'destroy'])->name('destroy');

        Route::get('qr-verify', [CertificateController::class, 'qrVerify'])->name('qr-verify');

        Route::get('digital-signature', [CertificateController::class, 'digitalSignature'])->name('digital-signature');
        Route::post('digital-signature/{id}', [CertificateController::class, 'addDigitalSignature'])->name('digital-signature.add');

        Route::get('templates', [CertificateController::class, 'templates'])->name('templates');
        Route::post('templates', [CertificateController::class, 'storeTemplate'])->name('templates.store');
        Route::put('templates/{id}', [CertificateController::class, 'updateTemplate'])->name('templates.update');
        Route::delete('templates/{id}', [CertificateController::class, 'deleteTemplate'])->name('templates.delete');
    });

    // Events
    Route::prefix('events')->name('events.')->middleware('permission:events.list|events.create|events.edit|events.delete')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index')->middleware('permission:events.list');
        Route::post('store', [EventController::class, 'store'])->name('store')->middleware('permission:events.create');
        Route::put('update/{id}', [EventController::class, 'update'])->name('update')->middleware('permission:events.edit');
        Route::delete('destroy/{id}', [EventController::class, 'destroy'])->name('destroy')->middleware('permission:events.delete');
        Route::get('competitions', [EventController::class, 'competitions'])->name('competitions')->middleware('permission:events.list');
        Route::get('workshops', [EventController::class, 'workshops'])->name('workshops')->middleware('permission:events.list');
        Route::get('sports', [EventController::class, 'sports'])->name('sports')->middleware('permission:events.list');
        Route::get('clubs', [EventController::class, 'clubs'])->name('clubs')->middleware('permission:events.list');
        Route::post('clubs/store', [EventController::class, 'storeClub'])->name('clubs.store')->middleware('permission:events.create');
        Route::put('clubs/update/{id}', [EventController::class, 'updateClub'])->name('clubs.update')->middleware('permission:events.edit');
        Route::delete('clubs/destroy/{id}', [EventController::class, 'destroyClub'])->name('clubs.destroy')->middleware('permission:events.delete');
        Route::post('clubs/members/store', [EventController::class, 'storeClubMember'])->name('clubs.members.store')->middleware('permission:events.create');
        Route::delete('clubs/members/destroy/{id}', [EventController::class, 'removeClubMember'])->name('clubs.members.destroy')->middleware('permission:events.delete');
        Route::get('calendar', [EventController::class, 'calendar'])->name('calendar')->middleware('permission:events.list');
        Route::get('registration', [EventController::class, 'registration'])->name('registration')->middleware('permission:events.list');
        Route::post('registration/store', [EventController::class, 'storeRegistration'])->name('registration.store')->middleware('permission:events.create');
        Route::delete('registration/destroy/{id}', [EventController::class, 'destroyRegistration'])->name('registration.destroy')->middleware('permission:events.delete');
        Route::get('attendance', [EventController::class, 'attendance'])->name('attendance')->middleware('permission:events.list');
        Route::put('attendance/mark/{id}', [EventController::class, 'markAttendance'])->name('attendance.mark')->middleware('permission:events.edit');
        Route::get('reports', [EventController::class, 'reports'])->name('reports')->middleware('permission:reports.list');
    });

    // Front Office (Reception)
    Route::prefix('front-office')->name('front-office.')->middleware('permission:communication.list|communication.create|communication.edit')->group(function () {
        Route::get('dashboard', [FrontOfficeController::class, 'dashboard'])->name('dashboard')->middleware('permission:communication.list');
        Route::get('/', [FrontOfficeController::class, 'visitors'])->name('index')->middleware('permission:communication.list');
        Route::post('visitors/store', [FrontOfficeController::class, 'storeVisitor'])->name('visitors.store')->middleware('permission:communication.create');
        Route::put('visitors/update/{id}', [FrontOfficeController::class, 'updateVisitor'])->name('visitors.update')->middleware('permission:communication.edit');
        Route::delete('visitors/delete/{id}', [FrontOfficeController::class, 'deleteVisitor'])->name('visitors.delete')->middleware('permission:communication.delete');
        Route::get('enquiries', [FrontOfficeController::class, 'enquiries'])->name('enquiries')->middleware('permission:communication.list');
        Route::post('enquiries/store', [FrontOfficeController::class, 'storeEnquiry'])->name('enquiries.store')->middleware('permission:communication.create');
        Route::put('enquiries/update/{id}', [FrontOfficeController::class, 'updateEnquiry'])->name('enquiries.update')->middleware('permission:communication.edit');
        Route::delete('enquiries/delete/{id}', [FrontOfficeController::class, 'deleteEnquiry'])->name('enquiries.delete')->middleware('permission:communication.delete');
        Route::get('call-logs', [FrontOfficeController::class, 'callLogs'])->name('call-logs')->middleware('permission:communication.list');
        Route::post('call-logs/store', [FrontOfficeController::class, 'storeCallLog'])->name('call-logs.store')->middleware('permission:communication.create');
        Route::put('call-logs/update/{id}', [FrontOfficeController::class, 'updateCallLog'])->name('call-logs.update')->middleware('permission:communication.edit');
        Route::delete('call-logs/delete/{id}', [FrontOfficeController::class, 'deleteCallLog'])->name('call-logs.delete')->middleware('permission:communication.delete');
        Route::get('appointments', [FrontOfficeController::class, 'appointments'])->name('appointments')->middleware('permission:communication.list');
        Route::post('appointments/store', [FrontOfficeController::class, 'storeAppointment'])->name('appointments.store')->middleware('permission:communication.create');
        Route::put('appointments/update/{id}', [FrontOfficeController::class, 'updateAppointment'])->name('appointments.update')->middleware('permission:communication.edit');
        Route::delete('appointments/delete/{id}', [FrontOfficeController::class, 'deleteAppointment'])->name('appointments.delete')->middleware('permission:communication.delete');
        Route::get('complaints', [FrontOfficeController::class, 'complaints'])->name('complaints')->middleware('permission:communication.list');
        Route::post('complaints/store', [FrontOfficeController::class, 'storeComplaint'])->name('complaints.store')->middleware('permission:communication.create');
        Route::put('complaints/update/{id}', [FrontOfficeController::class, 'updateComplaint'])->name('complaints.update')->middleware('permission:communication.edit');
        Route::delete('complaints/delete/{id}', [FrontOfficeController::class, 'deleteComplaint'])->name('complaints.delete')->middleware('permission:communication.delete');
    });

    // Books (Library)
    Route::prefix('books')->name('books.')->middleware('permission:library.list|library.create|library.edit|library.delete')->group(function () {
        Route::get('/', [BookController::class, 'index'])->name('index')->middleware('permission:library.list');
        Route::get('create', [BookController::class, 'create'])->name('create')->middleware('permission:library.create');
        Route::post('/', [BookController::class, 'store'])->name('store')->middleware('permission:library.create');
        Route::get('{book}', [BookController::class, 'show'])->name('show')->middleware('permission:library.list');
        Route::get('{book}/edit', [BookController::class, 'edit'])->name('edit')->middleware('permission:library.edit');
        Route::put('{book}', [BookController::class, 'update'])->name('update')->middleware('permission:library.edit');
        Route::delete('{book}', [BookController::class, 'destroy'])->name('destroy')->middleware('permission:library.delete');
    });

    // Book Issues
    Route::prefix('book-issues')->name('book-issues.')->middleware('permission:library.list')->group(function () {
        Route::get('/', [BookIssueController::class, 'index'])->name('index')->middleware('permission:library.list');
        Route::get('create', [BookIssueController::class, 'create'])->name('create')->middleware('permission:library.create');
        Route::post('/', [BookIssueController::class, 'store'])->name('store')->middleware('permission:library.create');
        Route::get('{issue}', [BookIssueController::class, 'show'])->name('show')->middleware('permission:library.list');
        Route::post('{issue}/return', [BookIssueController::class, 'returnBook'])->name('return')->middleware('permission:library.edit');
        Route::post('{issue}/renew', [BookIssueController::class, 'renew'])->name('renew')->middleware('permission:library.edit');
    });

    // Library pages
    Route::prefix('library')->name('library.')->middleware('permission:library.list')->group(function () {
        Route::get('members', [LibraryController::class, 'members'])->name('members');
        Route::get('barcode', [LibraryController::class, 'barcode'])->name('barcode');
        Route::get('return', [LibraryController::class, 'returnsList'])->name('return');
        Route::get('fines', [LibraryController::class, 'fines'])->name('fines');
        Route::get('ebook', [LibraryController::class, 'ebook'])->name('ebook');
        Route::get('reports', [LibraryController::class, 'reports'])->name('reports');
    });

    // Transport
    Route::prefix('transport')->name('transport.')->middleware('permission:transport.list|transport.create|transport.edit|transport.delete')->group(function () {
        Route::get('routes', [TransportController::class, 'routes'])->name('routes')->middleware('permission:transport.list');
        Route::get('routes/create', [TransportController::class, 'createRoute'])->name('routes.create')->middleware('permission:transport.create');
        Route::post('routes', [TransportController::class, 'storeRoute'])->name('routes.store')->middleware('permission:transport.create');
        Route::get('routes/{route}', [TransportController::class, 'showRoute'])->name('routes.show')->middleware('permission:transport.list');
        Route::get('vehicles', [TransportController::class, 'vehicles'])->name('vehicles')->middleware('permission:transport.list');
        Route::get('vehicles/create', [TransportController::class, 'createVehicle'])->name('vehicles.create')->middleware('permission:transport.create');
        Route::post('vehicles', [TransportController::class, 'storeVehicle'])->name('vehicles.store')->middleware('permission:transport.create');
        Route::get('vehicles/{vehicle}', [TransportController::class, 'showVehicle'])->name('vehicles.show')->middleware('permission:transport.list');
        Route::get('drivers', [TransportController::class, 'drivers'])->name('drivers')->middleware('permission:transport.list');
        Route::get('drivers/create', [TransportController::class, 'createDriver'])->name('drivers.create')->middleware('permission:transport.create');
        Route::post('drivers', [TransportController::class, 'storeDriver'])->name('drivers.store')->middleware('permission:transport.create');
        Route::get('allocations', [TransportController::class, 'allocations'])->name('allocations')->middleware('permission:transport.list');
    });

    // Hostels
    Route::prefix('hostels')->name('hostels.')->middleware('permission:hostel.list|hostel.create|hostel.edit|hostel.delete')->group(function () {
        Route::get('/', [HostelController::class, 'index'])->name('index')->middleware('permission:hostel.list');
        Route::post('/', [HostelController::class, 'store'])->name('store')->middleware('permission:hostel.create');
        Route::put('{id}', [HostelController::class, 'update'])->name('update')->middleware('permission:hostel.edit');
        Route::delete('{id}', [HostelController::class, 'destroy'])->name('destroy')->middleware('permission:hostel.delete');

        Route::get('allocations', [HostelController::class, 'allocations'])->name('allocations')->middleware('permission:hostel.list');
        Route::post('allocations', [HostelController::class, 'storeAllocation'])->name('allocations.store')->middleware('permission:hostel.create');
        Route::put('allocations/{id}', [HostelController::class, 'updateAllocation'])->name('allocations.update')->middleware('permission:hostel.edit');
        Route::delete('allocations/{id}', [HostelController::class, 'deleteAllocation'])->name('allocations.delete')->middleware('permission:hostel.delete');

        Route::get('beds', [HostelController::class, 'beds'])->name('beds')->middleware('permission:hostel.list');
        Route::put('beds/{id}', [HostelController::class, 'updateBed'])->name('beds.update')->middleware('permission:hostel.edit');

        Route::get('fees', [HostelController::class, 'fees'])->name('fees')->middleware('permission:hostel.list');
        Route::post('fees', [HostelController::class, 'storeFee'])->name('fees.store')->middleware('permission:hostel.create');
        Route::put('fees/{id}', [HostelController::class, 'updateFee'])->name('fees.update')->middleware('permission:hostel.edit');
        Route::delete('fees/{id}', [HostelController::class, 'deleteFee'])->name('fees.delete')->middleware('permission:hostel.delete');

        Route::get('visitors', [HostelController::class, 'visitors'])->name('visitors')->middleware('permission:hostel.list');
        Route::post('visitors', [HostelController::class, 'storeVisitor'])->name('visitors.store')->middleware('permission:hostel.create');
        Route::put('visitors/{id}', [HostelController::class, 'updateVisitor'])->name('visitors.update')->middleware('permission:hostel.edit');
        Route::delete('visitors/{id}', [HostelController::class, 'deleteVisitor'])->name('visitors.delete')->middleware('permission:hostel.delete');

        Route::get('leaves', [HostelController::class, 'leaves'])->name('leaves')->middleware('permission:hostel.list');
        Route::post('leaves', [HostelController::class, 'storeLeave'])->name('leaves.store')->middleware('permission:hostel.create');
        Route::put('leaves/{id}', [HostelController::class, 'updateLeave'])->name('leaves.update')->middleware('permission:hostel.edit');
        Route::delete('leaves/{id}', [HostelController::class, 'deleteLeave'])->name('leaves.delete')->middleware('permission:hostel.delete');

        Route::get('reports', [HostelController::class, 'reports'])->name('reports')->middleware('permission:reports.list');
    });

    // Alumni
    Route::prefix('alumni')->name('alumni.')->middleware('permission:alumni.list|alumni.create|alumni.edit|alumni.delete')->group(function () {
        Route::get('/', [AlumniController::class, 'index'])->name('index')->middleware('permission:alumni.list');
        Route::post('/', [AlumniController::class, 'store'])->name('store')->middleware('permission:alumni.create');
        Route::put('{id}', [AlumniController::class, 'update'])->name('update')->middleware('permission:alumni.edit');
        Route::delete('{id}', [AlumniController::class, 'destroy'])->name('destroy')->middleware('permission:alumni.delete');
        Route::post('{id}/verify', [AlumniController::class, 'verify'])->name('verify')->middleware('permission:alumni.edit');

        Route::get('portal', [AlumniController::class, 'portal'])->name('portal')->middleware('permission:alumni.list');

        Route::get('events', [AlumniController::class, 'events'])->name('events')->middleware('permission:alumni.list');
        Route::post('events', [AlumniController::class, 'storeEvent'])->name('events.store')->middleware('permission:alumni.create');
        Route::put('events/{id}', [AlumniController::class, 'updateEvent'])->name('events.update')->middleware('permission:alumni.edit');
        Route::delete('events/{id}', [AlumniController::class, 'deleteEvent'])->name('events.delete')->middleware('permission:alumni.delete');

        Route::get('donations', [AlumniController::class, 'donations'])->name('donations')->middleware('permission:alumni.list');
        Route::post('donations', [AlumniController::class, 'storeDonation'])->name('donations.store')->middleware('permission:alumni.create');
        Route::delete('donations/{id}', [AlumniController::class, 'deleteDonation'])->name('donations.delete')->middleware('permission:alumni.delete');

        Route::get('jobs', [AlumniController::class, 'jobs'])->name('jobs')->middleware('permission:alumni.list');
        Route::post('jobs', [AlumniController::class, 'storeJob'])->name('jobs.store')->middleware('permission:alumni.create');
        Route::put('jobs/{id}', [AlumniController::class, 'updateJob'])->name('jobs.update')->middleware('permission:alumni.edit');
        Route::delete('jobs/{id}', [AlumniController::class, 'deleteJob'])->name('jobs.delete')->middleware('permission:alumni.delete');

        Route::get('networking', [AlumniController::class, 'networking'])->name('networking')->middleware('permission:alumni.list');
        Route::post('networking', [AlumniController::class, 'storeNetworkProfile'])->name('networking.store')->middleware('permission:alumni.create');
        Route::put('networking/{id}', [AlumniController::class, 'updateNetworkProfile'])->name('networking.update')->middleware('permission:alumni.edit');
        Route::delete('networking/{id}', [AlumniController::class, 'deleteNetworkProfile'])->name('networking.delete')->middleware('permission:alumni.delete');

        Route::get('reports', [AlumniController::class, 'reports'])->name('reports')->middleware('permission:reports.list');
    });

    // Inventory
    Route::prefix('inventory')->name('inventory.')->middleware('permission:inventory.list|inventory.create|inventory.edit|inventory.delete')->group(function () {
        Route::get('items', [InventoryController::class, 'items'])->name('items')->middleware('permission:inventory.list');
        Route::get('items/create', [InventoryController::class, 'createItem'])->name('items.create')->middleware('permission:inventory.create');
        Route::post('items', [InventoryController::class, 'storeItem'])->name('items.store')->middleware('permission:inventory.create');
        Route::get('items/{item}', [InventoryController::class, 'showItem'])->name('items.show')->middleware('permission:inventory.list');
        Route::get('items/{item}/edit', [InventoryController::class, 'editItem'])->name('items.edit')->middleware('permission:inventory.edit');
        Route::put('items/{item}', [InventoryController::class, 'updateItem'])->name('items.update')->middleware('permission:inventory.edit');
        Route::get('categories', [InventoryController::class, 'categories'])->name('categories')->middleware('permission:inventory.list');
        Route::get('stock', [InventoryController::class, 'stock'])->name('stock')->middleware('permission:inventory.list');
        Route::get('vendors', [InventoryController::class, 'vendors'])->name('vendors')->middleware('permission:inventory.list');
        Route::get('vendors/create', [InventoryController::class, 'createVendor'])->name('vendors.create')->middleware('permission:inventory.create');
        Route::post('vendors', [InventoryController::class, 'storeVendor'])->name('vendors.store')->middleware('permission:inventory.create');
        Route::get('purchase-orders', [InventoryController::class, 'purchaseOrders'])->name('purchaseOrders')->middleware('permission:inventory.list');
        Route::get('stock-transfers', [InventoryController::class, 'stockTransfers'])->name('stock-transfers')->middleware('permission:inventory.list');
        Route::get('stock-audit', [InventoryController::class, 'stockAudit'])->name('stock-audit')->middleware('permission:inventory.list');
        Route::get('barcode', [InventoryController::class, 'barcode'])->name('barcode')->middleware('permission:inventory.list');
        Route::get('report', [InventoryController::class, 'report'])->name('report')->middleware('permission:reports.list');
    });

    // Accounting
    Route::prefix('accounting')->name('accounting.')->middleware('permission:accounts.list|accounts.create|accounts.edit|accounts.delete')->group(function () {
        Route::get('chart-of-accounts', [AccountingController::class, 'chartOfAccounts'])->name('chart-of-accounts')->middleware('permission:accounts.list');
        Route::get('general-ledger', [AccountingController::class, 'generalLedger'])->name('general-ledger')->middleware('permission:accounts.list');
        Route::get('journal-entry', [AccountingController::class, 'journalEntry'])->name('journal-entry')->middleware('permission:accounts.list');
        Route::get('payable', [AccountingController::class, 'payable'])->name('payable')->middleware('permission:accounts.list');
        Route::get('receivable', [AccountingController::class, 'receivable'])->name('receivable')->middleware('permission:accounts.list');
        Route::get('trial-balance', [AccountingController::class, 'trialBalance'])->name('trial-balance')->middleware('permission:accounts.list');
        Route::get('cash-book', [AccountingController::class, 'cashBook'])->name('cash-book')->middleware('permission:accounts.list');
        Route::get('bank-reconciliation', [AccountingController::class, 'bankReconciliation'])->name('bank-reconciliation')->middleware('permission:accounts.list');
        Route::get('budget', [AccountingController::class, 'budget'])->name('budget')->middleware('permission:accounts.list');
        Route::get('statements', [AccountingController::class, 'statements'])->name('statements')->middleware('permission:accounts.list');
        Route::match(['get', 'post'], 'currencies', [AccountingController::class, 'currencies'])->name('currencies')->middleware('permission:accounts.list');
        Route::match(['get', 'post'], 'exchange-rates', [AccountingController::class, 'exchangeRates'])->name('exchange-rates')->middleware('permission:accounts.list');
        Route::get('report', [AccountingController::class, 'report'])->name('report')->middleware('permission:reports.list');
    });

    // Health Records
    Route::prefix('health')->name('health.')->middleware('permission:students.list')->group(function () {
        Route::get('student-records', [HealthController::class, 'studentHealthRecords'])->name('student-records');
        Route::post('student-records', [HealthController::class, 'storeHealthRecord'])->name('student-records.store');
        Route::put('student-records/{id}', [HealthController::class, 'updateHealthRecord'])->name('student-records.update');
        Route::delete('student-records/{id}', [HealthController::class, 'deleteHealthRecord'])->name('student-records.delete');

        Route::get('vaccinations', [HealthController::class, 'vaccinations'])->name('vaccinations');
        Route::post('vaccinations', [HealthController::class, 'storeVaccination'])->name('vaccinations.store');
        Route::put('vaccinations/{id}', [HealthController::class, 'updateVaccination'])->name('vaccinations.update');
        Route::delete('vaccinations/{id}', [HealthController::class, 'deleteVaccination'])->name('vaccinations.delete');

        Route::get('medical-history', [HealthController::class, 'medicalHistory'])->name('medical-history');
        Route::put('medical-history/{id}', [HealthController::class, 'updateMedicalHistory'])->name('medical-history.update');

        Route::get('checkups', [HealthController::class, 'checkups'])->name('checkups');

        Route::get('medicines', [HealthController::class, 'medicines'])->name('medicines');
        Route::post('medicines', [HealthController::class, 'storeMedicine'])->name('medicines.store');
        Route::put('medicines/{id}', [HealthController::class, 'updateMedicine'])->name('medicines.update');
        Route::delete('medicines/{id}', [HealthController::class, 'deleteMedicine'])->name('medicines.delete');

        Route::get('emergency-contacts', [HealthController::class, 'emergencyContacts'])->name('emergency-contacts');
        Route::post('emergency-contacts', [HealthController::class, 'storeEmergencyContact'])->name('emergency-contacts.store');
        Route::put('emergency-contacts/{id}', [HealthController::class, 'updateEmergencyContact'])->name('emergency-contacts.update');
        Route::delete('emergency-contacts/{id}', [HealthController::class, 'deleteEmergencyContact'])->name('emergency-contacts.delete');

        Route::get('reports', [HealthController::class, 'reports'])->name('reports');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->middleware('permission:reports.list|reports.generate|reports.export')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index')->middleware('permission:reports.list');
        Route::get('student', [ReportController::class, 'studentReport'])->name('student')->middleware('permission:reports.list');
        Route::get('attendance', [ReportController::class, 'attendanceReport'])->name('attendance')->middleware('permission:reports.list');
        Route::get('fee', [ReportController::class, 'feeReport'])->name('fee')->middleware('permission:reports.list');
        Route::get('employee', [ReportController::class, 'employeeReport'])->name('employee')->middleware('permission:reports.list');
    });

    // MIS Reports
    Route::prefix('mis')->name('mis.')->middleware('permission:reports.list|reports.generate')->group(function () {
        Route::get('executive-dashboard', [MisController::class, 'executiveDashboard'])->name('executive-dashboard');
        Route::get('kpi-tracking', [MisController::class, 'kpiTracking'])->name('kpi-tracking');
        Route::get('academic-analytics', [MisController::class, 'academicAnalytics'])->name('academic-analytics');
        Route::get('financial-analytics', [MisController::class, 'financialAnalytics'])->name('financial-analytics');
        Route::get('student-analytics', [MisController::class, 'studentAnalytics'])->name('student-analytics');
        Route::get('attendance-analytics', [MisController::class, 'attendanceAnalytics'])->name('attendance-analytics');
        Route::get('ai-predictive-analytics', [MisController::class, 'aiPredictiveAnalytics'])->name('ai-predictive-analytics');
        Route::get('custom-reports', [MisController::class, 'customReports'])->name('custom-reports');
        Route::post('custom-reports', [MisController::class, 'storeCustomReport'])->name('custom-reports.store');
        Route::delete('custom-reports/{id}', [MisController::class, 'deleteCustomReport'])->name('custom-reports.delete');
        Route::post('custom-reports/schedule', [MisController::class, 'scheduleReport'])->name('custom-reports.schedule');
        Route::put('custom-reports/{id}/unschedule', [MisController::class, 'unscheduleReport'])->name('custom-reports.unschedule');
    });

    // Asset Management
    Route::prefix('asset')->name('asset.')->middleware('permission:inventory.list|inventory.create|inventory.edit|inventory.delete')->group(function () {
        Route::get('/', [AssetController::class, 'index'])->name('index')->middleware('permission:inventory.list');
        Route::post('/', [AssetController::class, 'store'])->name('store')->middleware('permission:inventory.create');
        Route::put('{id}', [AssetController::class, 'update'])->name('update')->middleware('permission:inventory.edit');
        Route::delete('{id}', [AssetController::class, 'destroy'])->name('destroy')->middleware('permission:inventory.delete');

        Route::get('tagging', [AssetController::class, 'tagging'])->name('tagging')->middleware('permission:inventory.list');
        Route::put('tagging/{id}', [AssetController::class, 'updateTag'])->name('tagging.update')->middleware('permission:inventory.edit');

        Route::get('barcode-tracking', [AssetController::class, 'barcodeTracking'])->name('barcode-tracking')->middleware('permission:inventory.list');

        Route::get('allocations', [AssetController::class, 'allocations'])->name('allocations')->middleware('permission:inventory.list');
        Route::post('allocations', [AssetController::class, 'storeAllocation'])->name('allocations.store')->middleware('permission:inventory.create');
        Route::put('allocations/{id}/return', [AssetController::class, 'returnAllocation'])->name('allocations.return')->middleware('permission:inventory.edit');
        Route::delete('allocations/{id}', [AssetController::class, 'deleteAllocation'])->name('allocations.delete')->middleware('permission:inventory.delete');

        Route::get('maintenance', [AssetController::class, 'maintenance'])->name('maintenance')->middleware('permission:inventory.list');
        Route::post('maintenance', [AssetController::class, 'storeMaintenance'])->name('maintenance.store')->middleware('permission:inventory.create');
        Route::put('maintenance/{id}', [AssetController::class, 'updateMaintenance'])->name('maintenance.update')->middleware('permission:inventory.edit');
        Route::delete('maintenance/{id}', [AssetController::class, 'deleteMaintenance'])->name('maintenance.delete')->middleware('permission:inventory.delete');

        Route::get('depreciation', [AssetController::class, 'depreciation'])->name('depreciation')->middleware('permission:inventory.list');
        Route::post('depreciation', [AssetController::class, 'storeDepreciation'])->name('depreciation.store')->middleware('permission:inventory.create');
        Route::delete('depreciation/{id}', [AssetController::class, 'deleteDepreciation'])->name('depreciation.delete')->middleware('permission:inventory.delete');

        Route::get('audit', [AssetController::class, 'audit'])->name('audit')->middleware('permission:inventory.list');
        Route::post('audit', [AssetController::class, 'storeAudit'])->name('audit.store')->middleware('permission:inventory.create');
        Route::delete('audit/{id}', [AssetController::class, 'deleteAudit'])->name('audit.delete')->middleware('permission:inventory.delete');

        Route::get('reports', [AssetController::class, 'reports'])->name('reports')->middleware('permission:reports.list');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->middleware('permission:settings.list|settings.create|settings.edit')->group(function () {
        Route::get('school', [SettingsController::class, 'school'])->name('school')->middleware('permission:settings.list');
        Route::put('school', [SettingsController::class, 'updateSchool'])->name('school.update')->middleware('permission:settings.edit');
        Route::get('academic-years', [SettingsController::class, 'academicYears'])->name('academicYears')->middleware('permission:academic_years.list');
        Route::post('academic-years', [SettingsController::class, 'storeAcademicYear'])->name('academicYears.store')->middleware('permission:academic_years.create');
        Route::get('general', [SettingsController::class, 'general'])->name('general')->middleware('permission:settings.list');
        Route::put('general', [SettingsController::class, 'updateGeneral'])->name('general.update')->middleware('permission:settings.edit');
    });

    // Homepage Sections
    Route::prefix('homepage')->name('homepage.')->middleware('permission:settings.list')->group(function () {
        Route::get('hero', [HomepageController::class, 'hero'])->name('hero');
        Route::put('hero', [HomepageController::class, 'heroUpdate'])->name('hero.update');
        Route::get('navigation', [HomepageController::class, 'navigation'])->name('navigation');
        Route::put('navigation', [HomepageController::class, 'navigationUpdate'])->name('navigation.update');
        Route::get('about', [HomepageController::class, 'about'])->name('about');
        Route::put('about', [HomepageController::class, 'aboutUpdate'])->name('about.update');
        Route::get('services', [HomepageController::class, 'services'])->name('services');
        Route::put('services', [HomepageController::class, 'servicesUpdate'])->name('services.update');
        Route::get('features', [HomepageController::class, 'features'])->name('features');
        Route::put('features', [HomepageController::class, 'featuresUpdate'])->name('features.update');
        Route::get('products', [HomepageController::class, 'products'])->name('products');
        Route::put('products', [HomepageController::class, 'productsUpdate'])->name('products.update');
        Route::get('portfolio', [HomepageController::class, 'portfolio'])->name('portfolio');
        Route::put('portfolio', [HomepageController::class, 'portfolioUpdate'])->name('portfolio.update');
        Route::get('testimonials', [HomepageController::class, 'testimonials'])->name('testimonials');
        Route::put('testimonials', [HomepageController::class, 'testimonialsUpdate'])->name('testimonials.update');
        Route::get('team', [HomepageController::class, 'team'])->name('team');
        Route::put('team', [HomepageController::class, 'teamUpdate'])->name('team.update');
        Route::get('statistics', [HomepageController::class, 'statistics'])->name('statistics');
        Route::put('statistics', [HomepageController::class, 'statisticsUpdate'])->name('statistics.update');
        Route::get('video', [HomepageController::class, 'video'])->name('video');
        Route::put('video', [HomepageController::class, 'videoUpdate'])->name('video.update');
        Route::get('faq', [HomepageController::class, 'faq'])->name('faq');
        Route::put('faq', [HomepageController::class, 'faqUpdate'])->name('faq.update');
        Route::get('pricing', [HomepageController::class, 'pricing'])->name('pricing');
        Route::put('pricing', [HomepageController::class, 'pricingUpdate'])->name('pricing.update');
        Route::get('blog', [HomepageController::class, 'blog'])->name('blog');
        Route::put('blog', [HomepageController::class, 'blogUpdate'])->name('blog.update');
        Route::get('cta', [HomepageController::class, 'cta'])->name('cta');
        Route::put('cta', [HomepageController::class, 'ctaUpdate'])->name('cta.update');
        Route::get('newsletter', [HomepageController::class, 'newsletter'])->name('newsletter');
        Route::put('newsletter', [HomepageController::class, 'newsletterUpdate'])->name('newsletter.update');
        Route::get('partners', [HomepageController::class, 'partners'])->name('partners');
        Route::put('partners', [HomepageController::class, 'partnersUpdate'])->name('partners.update');
        Route::get('gallery', [HomepageController::class, 'gallery'])->name('gallery');
        Route::put('gallery', [HomepageController::class, 'galleryUpdate'])->name('gallery.update');
        Route::get('contact', [HomepageController::class, 'contact'])->name('contact');
        Route::put('contact', [HomepageController::class, 'contactUpdate'])->name('contact.update');
        Route::get('social-media', [HomepageController::class, 'socialMedia'])->name('social-media');
        Route::put('social-media', [HomepageController::class, 'socialMediaUpdate'])->name('social-media.update');
        Route::get('footer-widgets', [HomepageController::class, 'footerWidgets'])->name('footer-widgets');
        Route::put('footer-widgets', [HomepageController::class, 'footerWidgetsUpdate'])->name('footer-widgets.update');
        Route::get('theme', [HomepageController::class, 'theme'])->name('theme');
        Route::put('theme', [HomepageController::class, 'themeUpdate'])->name('theme.update');
        Route::get('seo', [HomepageController::class, 'seo'])->name('seo');
        Route::put('seo', [HomepageController::class, 'seoUpdate'])->name('seo.update');
        Route::get('section-manager', [HomepageController::class, 'sectionManager'])->name('section-manager');
        Route::put('section-manager', [HomepageController::class, 'sectionManagerUpdate'])->name('section-manager.update');
    });

    // Menu Management
    Route::prefix('menu-manage')->name('menu-manage.')->middleware('permission:settings.edit')->group(function () {
        Route::get('/', [MenuController::class, 'index'])->name('index');
        Route::post('/', [MenuController::class, 'store'])->name('store');
        Route::put('{id}', [MenuController::class, 'update'])->name('update');
        Route::delete('{id}', [MenuController::class, 'destroy'])->name('destroy');
    });

    // Custom Pages
    Route::prefix('custom-pages')->name('custom-pages.')->middleware('permission:settings.edit')->group(function () {
        Route::get('/', [CustomPageController::class, 'index'])->name('index');
        Route::get('create', [CustomPageController::class, 'create'])->name('create');
        Route::post('/', [CustomPageController::class, 'store'])->name('store');
        Route::get('{id}/edit', [CustomPageController::class, 'edit'])->name('edit');
        Route::put('{id}', [CustomPageController::class, 'update'])->name('update');
        Route::get('{id}/builder', [CustomPageController::class, 'builder'])->name('builder');
        Route::post('{id}/builder/section', [CustomPageController::class, 'builderStoreSection'])->name('builder.section.store');
        Route::put('{id}/builder/section/{sectionId}', [CustomPageController::class, 'builderUpdateSection'])->name('builder.section.update');
        Route::post('{id}/builder/reorder', [CustomPageController::class, 'builderReorder'])->name('builder.reorder');
        Route::post('{id}/builder/section/{sectionId}/toggle', [CustomPageController::class, 'builderToggleSection'])->name('builder.section.toggle');
        Route::delete('{id}/builder/section/{sectionId}', [CustomPageController::class, 'builderDeleteSection'])->name('builder.section.delete');
        Route::delete('{id}', [CustomPageController::class, 'destroy'])->name('destroy');
        Route::post('upload-image', [CustomPageController::class, 'uploadImage'])->name('upload-image');
    });

    // User Setup
    Route::prefix('users')->name('users.')->middleware('permission:users.list|users.create|users.edit|users.delete')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index')->middleware('permission:users.list');
        Route::get('create', [UserController::class, 'create'])->name('create')->middleware('permission:users.create');
        Route::post('/', [UserController::class, 'store'])->name('store')->middleware('permission:users.create');
        Route::get('{id}/edit', [UserController::class, 'edit'])->name('edit')->middleware('permission:users.edit');
        Route::put('{id}', [UserController::class, 'update'])->name('update')->middleware('permission:users.edit');
        Route::delete('{id}', [UserController::class, 'destroy'])->name('destroy')->middleware('permission:users.delete');
    });

    Route::prefix('roles')->name('roles.')->middleware('permission:roles.list|roles.create|roles.edit|roles.delete')->group(function () {
        Route::get('/', [RolePermissionController::class, 'index'])->name('index')->middleware('permission:roles.list');
        Route::get('create', [RolePermissionController::class, 'create'])->name('create')->middleware('permission:roles.create');
        Route::post('/', [RolePermissionController::class, 'store'])->name('store')->middleware('permission:roles.create');
        Route::get('{id}/edit', [RolePermissionController::class, 'edit'])->name('edit')->middleware('permission:roles.edit');
        Route::put('{id}', [RolePermissionController::class, 'update'])->name('update')->middleware('permission:roles.edit');
        Route::delete('{id}', [RolePermissionController::class, 'destroy'])->name('destroy')->middleware('permission:roles.delete');
    });

    // Activity Logs
    Route::get('activity-logs', function () {
        return view('activity-logs.index');
    })->name('activity-logs.index')->middleware('permission:audit_logs.view');

    // AI Integration
    Route::prefix('ai')->name('ai.')->middleware('permission:ai.list|ai.create|ai.edit|ai.delete')->group(function () {
        Route::get('chat', [AIController::class, 'chat'])->name('chat')->middleware('permission:ai.list');
        Route::post('chat', [AIController::class, 'storeChat'])->name('chat.store')->middleware('permission:ai.create');
        Route::get('performance-prediction', [AIController::class, 'performancePrediction'])->name('performance-prediction')->middleware('permission:ai.list');
        Route::get('attendance-prediction', [AIController::class, 'attendancePrediction'])->name('attendance-prediction')->middleware('permission:ai.list');
        Route::get('fee-defaulter-prediction', [AIController::class, 'feeDefaulterPrediction'])->name('fee-defaulter-prediction')->middleware('permission:ai.list');
        Route::get('report-generator', [AIController::class, 'reportGenerator'])->name('report-generator')->middleware('permission:ai.list');
        Route::post('report-generator', [AIController::class, 'generateReport'])->name('report-generator.store')->middleware('permission:ai.create');
        Route::get('timetable-generator', [AIController::class, 'timetableGenerator'])->name('timetable-generator')->middleware('permission:ai.list');
        Route::post('timetable-generator', [AIController::class, 'generateTimetable'])->name('timetable-generator.store')->middleware('permission:ai.create');
        Route::get('analytics-dashboard', [AIController::class, 'analyticsDashboard'])->name('analytics-dashboard')->middleware('permission:ai.list');
        Route::get('recommendation-engine', [AIController::class, 'recommendationEngine'])->name('recommendation-engine')->middleware('permission:ai.list');
        Route::post('recommendation-engine', [AIController::class, 'storeRecommendation'])->name('recommendation-engine.store')->middleware('permission:ai.create');
    });

    // Admin-only routes
    Route::middleware('role:super_admin,school_admin,principal')->group(function () {
        // Admin-specific routes can be added here
    });
});
