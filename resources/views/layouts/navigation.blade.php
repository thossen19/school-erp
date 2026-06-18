<nav class="sidebar-nav">
    <div class="sidebar-filter position-relative">
        <i class="fas fa-search filter-icon"></i>
        <input type="text" id="sidebarSearch" placeholder="Search menus..." oninput="filterSidebar(this.value)">
    </div>

    <div class="nav-category">Main</div>
    <div class="nav-item">
        <a href="{{ route_if_exists('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i><span class="link-text">Dashboard</span>
        </a>
    </div>

    <div class="nav-category">Admissions</div>
    <div class="nav-item">
        <a class="nav-link has-submenu" data-bs-toggle="collapse" href="#admissionMenu" role="button" aria-expanded="false">
            <i class="fas fa-door-open"></i><span class="link-text">Admission</span>
        </a>
        <div class="collapse" id="admissionMenu">
            <div class="collapse-submenu">
                <a href="{{ route_if_exists('admissions.index') }}" class="nav-link {{ request()->routeIs('admissions.index') ? 'active' : '' }}"><span class="link-text">Enquiries & Applications</span></a>
                <a href="{{ route_if_exists('admissions.online-portal') }}" class="nav-link {{ request()->routeIs('admissions.online-portal') ? 'active' : '' }}"><span class="link-text">Online Portal</span></a>
                <a href="{{ route_if_exists('admissions.application-forms') }}" class="nav-link {{ request()->routeIs('admissions.application-forms') ? 'active' : '' }}"><span class="link-text">Application Forms</span></a>
                <a href="{{ route_if_exists('admissions.admission-enquiry') }}" class="nav-link {{ request()->routeIs('admissions.admission-enquiry') ? 'active' : '' }}"><span class="link-text">Admission Enquiry</span></a>
                <a href="{{ route_if_exists('admissions.lead-management') }}" class="nav-link {{ request()->routeIs('admissions.lead-management') ? 'active' : '' }}"><span class="link-text">Lead Management</span></a>
                <a href="{{ route_if_exists('admissions.entrance-exam') }}" class="nav-link {{ request()->routeIs('admissions.entrance-exam') ? 'active' : '' }}"><span class="link-text">Entrance Exams</span></a>
                <a href="{{ route_if_exists('admissions.student-registration') }}" class="nav-link {{ request()->routeIs('admissions.student-registration') ? 'active' : '' }}"><span class="link-text">Student Registration</span></a>
                <a href="{{ route_if_exists('admissions.document-upload') }}" class="nav-link {{ request()->routeIs('admissions.document-upload') ? 'active' : '' }}"><span class="link-text">Document Upload</span></a>
                <a href="{{ route_if_exists('admissions.admission-workflow') }}" class="nav-link {{ request()->routeIs('admissions.admission-workflow') ? 'active' : '' }}"><span class="link-text">Admission Workflow</span></a>
                <a href="{{ route_if_exists('admissions.admission-approval') }}" class="nav-link {{ request()->routeIs('admissions.admission-approval') ? 'active' : '' }}"><span class="link-text">Admission Approval</span></a>
                <a href="{{ route_if_exists('admissions.merit-list') }}" class="nav-link {{ request()->routeIs('admissions.merit-list') ? 'active' : '' }}"><span class="link-text">Merit List</span></a>
                <a href="{{ route_if_exists('admissions.merit-list-generation') }}" class="nav-link {{ request()->routeIs('admissions.merit-list-generation') ? 'active' : '' }}"><span class="link-text">Merit List Generation</span></a>
                <a href="{{ route_if_exists('admissions.interview-scheduling') }}" class="nav-link {{ request()->routeIs('admissions.interview-scheduling') ? 'active' : '' }}"><span class="link-text">Interview Scheduling</span></a>
                <a href="{{ route_if_exists('admissions.waiting-list') }}" class="nav-link {{ request()->routeIs('admissions.waiting-list') ? 'active' : '' }}"><span class="link-text">Waiting List</span></a>
                <a href="{{ route_if_exists('admissions.admission-fee-collection') }}" class="nav-link {{ request()->routeIs('admissions.admission-fee-collection') ? 'active' : '' }}"><span class="link-text">Fee Collection</span></a>
                <a href="{{ route_if_exists('admissions.student-id-generation') }}" class="nav-link {{ request()->routeIs('admissions.student-id-generation') ? 'active' : '' }}"><span class="link-text">Student ID Generation</span></a>
                <a href="{{ route_if_exists('admissions.parent-registration') }}" class="nav-link {{ request()->routeIs('admissions.parent-registration') ? 'active' : '' }}"><span class="link-text">Parent Registration</span></a>
                <a href="{{ route_if_exists('admissions.admission-reports') }}" class="nav-link {{ request()->routeIs('admissions.admission-reports') ? 'active' : '' }}"><span class="link-text">Admission Reports</span></a>
            </div>
        </div>
    </div>

    <div class="nav-category">Academics</div>
    <div class="nav-item">
        <a class="nav-link has-submenu" data-bs-toggle="collapse" href="#academicMenu" role="button" aria-expanded="false">
            <i class="fas fa-book-open"></i><span class="link-text">Academic</span>
        </a>
        <div class="collapse" id="academicMenu">
            <div class="collapse-submenu">
                <a href="{{ route_if_exists('academic.index') }}" class="nav-link {{ request()->routeIs('academic.index') ? 'active' : '' }}"><span class="link-text">Dashboard</span></a>
                <a href="{{ route_if_exists('academic.academic-years') }}" class="nav-link {{ request()->routeIs('academic.academic-years') ? 'active' : '' }}"><span class="link-text">Academic Year</span></a>
                <a href="{{ route_if_exists('academic.classes') }}" class="nav-link {{ request()->routeIs('academic.classes') ? 'active' : '' }}"><span class="link-text">Class Management</span></a>
                <a href="{{ route_if_exists('academic.sections') }}" class="nav-link {{ request()->routeIs('academic.sections') ? 'active' : '' }}"><span class="link-text">Section Management</span></a>
                <a href="{{ route_if_exists('academic.subjects') }}" class="nav-link {{ request()->routeIs('academic.subjects') ? 'active' : '' }}"><span class="link-text">Subject Management</span></a>
                <a href="{{ route_if_exists('academic.curriculum') }}" class="nav-link {{ request()->routeIs('academic.curriculum') ? 'active' : '' }}"><span class="link-text">Curriculum Management</span></a>
                <a href="{{ route_if_exists('academic.lesson-plans') }}" class="nav-link {{ request()->routeIs('academic.lesson-plans') ? 'active' : '' }}"><span class="link-text">Lesson Plans</span></a>
                <a href="{{ route_if_exists('academic.assignments') }}" class="nav-link {{ request()->routeIs('academic.assignments') ? 'active' : '' }}"><span class="link-text">Assignment Management</span></a>
                <a href="{{ route_if_exists('academic.homework') }}" class="nav-link {{ request()->routeIs('academic.homework') ? 'active' : '' }}"><span class="link-text">Homework</span></a>
                <a href="{{ route_if_exists('academic.study-materials') }}" class="nav-link {{ request()->routeIs('academic.study-materials') ? 'active' : '' }}"><span class="link-text">Study Materials</span></a>
                <a href="{{ route_if_exists('academic.teacher-diary') }}" class="nav-link {{ request()->routeIs('academic.teacher-diary') ? 'active' : '' }}"><span class="link-text">Teacher Diary</span></a>
                <a href="{{ route_if_exists('academic.reports') }}" class="nav-link {{ request()->routeIs('academic.reports') ? 'active' : '' }}"><span class="link-text">Reports</span></a>
            </div>
        </div>
    </div>

    <div class="nav-category">Students</div>
    <div class="nav-item">
        <a class="nav-link has-submenu" data-bs-toggle="collapse" href="#studentMenu" role="button" aria-expanded="false">
            <i class="fas fa-user-graduate"></i><span class="link-text">Student Management</span>
        </a>
        <div class="collapse" id="studentMenu">
            <div class="collapse-submenu">
                <a href="{{ url('/students') }}" class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}"><span class="link-text">All Students</span></a>
                <a href="{{ url('/student-houses') }}" class="nav-link {{ request()->routeIs('student-houses.*') ? 'active' : '' }}"><span class="link-text">Houses</span></a>
                <a href="{{ url('/student-groups') }}" class="nav-link {{ request()->routeIs('student-groups.*') ? 'active' : '' }}"><span class="link-text">Groups</span></a>
                <a href="{{ url('/student-documents') }}" class="nav-link {{ request()->routeIs('student-documents.*') ? 'active' : '' }}"><span class="link-text">Documents</span></a>
                <a href="{{ url('/student-disciplines') }}" class="nav-link {{ request()->routeIs('student-disciplines.*') ? 'active' : '' }}"><span class="link-text">Disciplines</span></a>
                <a href="{{ url('/student-awards') }}" class="nav-link {{ request()->routeIs('student-awards.*') ? 'active' : '' }}"><span class="link-text">Awards</span></a>
                <a href="{{ url('/student-promotions') }}" class="nav-link {{ request()->routeIs('student-promotions.*') ? 'active' : '' }}"><span class="link-text">Promotions</span></a>
                <a href="{{ url('/student-transfers') }}" class="nav-link {{ request()->routeIs('student-transfers.*') ? 'active' : '' }}"><span class="link-text">Transfers</span></a>
            </div>
        </div>
    </div>

    <div class="nav-category">Attendance</div>
    <div class="nav-item">
        <a class="nav-link has-submenu" data-bs-toggle="collapse" href="#attendanceMenu" role="button" aria-expanded="false">
            <i class="fas fa-calendar-check"></i><span class="link-text">Attendance</span>
        </a>
        <div class="collapse" id="attendanceMenu">
            <div class="collapse-submenu">
                <a href="{{ route_if_exists('attendance.daily') }}" class="nav-link {{ request()->routeIs('attendance.daily') ? 'active' : '' }}"><span class="link-text">Daily Attendance</span></a>
                <a href="{{ route_if_exists('attendance.period') }}" class="nav-link {{ request()->routeIs('attendance.period') ? 'active' : '' }}"><span class="link-text">Period Attendance</span></a>
                <a href="{{ route_if_exists('attendance.subject') }}" class="nav-link {{ request()->routeIs('attendance.subject') ? 'active' : '' }}"><span class="link-text">Subject Attendance</span></a>
                <a href="{{ route_if_exists('attendance.rfid') }}" class="nav-link {{ request()->routeIs('attendance.rfid') ? 'active' : '' }}"><span class="link-text">RFID Attendance</span></a>
                <a href="{{ route_if_exists('attendance.uhf') }}" class="nav-link {{ request()->routeIs('attendance.uhf') ? 'active' : '' }}"><span class="link-text">UHF Attendance</span></a>
                <a href="{{ route_if_exists('attendance.biometric') }}" class="nav-link {{ request()->routeIs('attendance.biometric') ? 'active' : '' }}"><span class="link-text">Biometric Attendance</span></a>
                <a href="{{ route_if_exists('attendance.face-recognition') }}" class="nav-link {{ request()->routeIs('attendance.face-recognition') ? 'active' : '' }}"><span class="link-text">Face Recognition Ready</span></a>
                <a href="{{ route_if_exists('attendance.correction') }}" class="nav-link {{ request()->routeIs('attendance.correction') ? 'active' : '' }}"><span class="link-text">Attendance Correction</span></a>
                <a href="{{ route_if_exists('attendance.late-entry') }}" class="nav-link {{ request()->routeIs('attendance.late-entry') ? 'active' : '' }}"><span class="link-text">Late Entry Tracking</span></a>
                <a href="{{ route_if_exists('attendance.leave-tracking') }}" class="nav-link {{ request()->routeIs('attendance.leave-tracking') ? 'active' : '' }}"><span class="link-text">Leave Tracking</span></a>
                <a href="{{ route_if_exists('attendance.parent-notification') }}" class="nav-link {{ request()->routeIs('attendance.parent-notification') ? 'active' : '' }}"><span class="link-text">Parent Notification</span></a>
                <a href="{{ route_if_exists('attendance.analytics') }}" class="nav-link {{ request()->routeIs('attendance.analytics') ? 'active' : '' }}"><span class="link-text">Attendance Analytics</span></a>
                <a href="{{ route_if_exists('attendance.reports') }}" class="nav-link {{ request()->routeIs('attendance.reports') ? 'active' : '' }}"><span class="link-text">Attendance Reports</span></a>
                <hr class="my-1 mx-3">
                <a href="{{ route('students.portal') }}" class="nav-link {{ request()->routeIs('students.portal') ? 'active' : '' }}"><span class="link-text">Student Portal</span></a>
            </div>
        </div>
    </div>

    <div class="nav-category">Fees</div>
    <div class="nav-item">
        <a class="nav-link has-submenu" data-bs-toggle="collapse" href="#feesMenu" role="button" aria-expanded="false">
            <i class="fas fa-money-bill-wave"></i><span class="link-text">Fees</span>
        </a>
        <div class="collapse" id="feesMenu">
            <div class="collapse-submenu">
                <a href="{{ route_if_exists('fees.fee-structure') }}" class="nav-link {{ request()->routeIs('fees.fee-structure') ? 'active' : '' }}"><span class="link-text">Fee Structure</span></a>
                <a href="{{ route_if_exists('fees.fee-categories') }}" class="nav-link {{ request()->routeIs('fees.fee-categories') ? 'active' : '' }}"><span class="link-text">Fee Categories</span></a>
                <a href="{{ route_if_exists('fees.installment-plans') }}" class="nav-link {{ request()->routeIs('fees.installment-plans') ? 'active' : '' }}"><span class="link-text">Installment Plans</span></a>
                <a href="{{ route_if_exists('fees.scholarship-management') }}" class="nav-link {{ request()->routeIs('fees.scholarship-management') ? 'active' : '' }}"><span class="link-text">Scholarship Management</span></a>
                <a href="{{ route_if_exists('fees.discount-management') }}" class="nav-link {{ request()->routeIs('fees.discount-management') ? 'active' : '' }}"><span class="link-text">Discount Management</span></a>
                <a href="{{ route_if_exists('fees.fine-management') }}" class="nav-link {{ request()->routeIs('fees.fine-management') ? 'active' : '' }}"><span class="link-text">Fine Management</span></a>
                <a href="{{ route_if_exists('fees.fee-collection') }}" class="nav-link {{ request()->routeIs('fees.fee-collection') ? 'active' : '' }}"><span class="link-text">Fee Collection</span></a>
                <a href="{{ route_if_exists('fees.online-payment') }}" class="nav-link {{ request()->routeIs('fees.online-payment') ? 'active' : '' }}"><span class="link-text">Online Payment</span></a>
                <a href="{{ route_if_exists('fees.receipt-generation') }}" class="nav-link {{ request()->routeIs('fees.receipt-generation') ? 'active' : '' }}"><span class="link-text">Receipt Generation</span></a>
                <a href="{{ route_if_exists('fees.due-tracking') }}" class="nav-link {{ request()->routeIs('fees.due-tracking') ? 'active' : '' }}"><span class="link-text">Due Tracking</span></a>
                <a href="{{ route_if_exists('fees.auto-reminder') }}" class="nav-link {{ request()->routeIs('fees.auto-reminder') ? 'active' : '' }}"><span class="link-text">Auto Reminder</span></a>
                <a href="{{ route_if_exists('fees.payment-reconciliation') }}" class="nav-link {{ request()->routeIs('fees.payment-reconciliation') ? 'active' : '' }}"><span class="link-text">Payment Reconciliation</span></a>
                <a href="{{ route_if_exists('fees.financial-reports') }}" class="nav-link {{ request()->routeIs('fees.financial-reports') ? 'active' : '' }}"><span class="link-text">Financial Reports</span></a>
            </div>
        </div>
    </div>

    <div class="nav-category">Timetable</div>
    <div class="nav-item">
        <a class="nav-link has-submenu" data-bs-toggle="collapse" href="#timetableMenu" role="button" aria-expanded="false">
            <i class="fas fa-clock"></i><span class="link-text">Timetable</span>
        </a>
        <div class="collapse" id="timetableMenu">
            <div class="collapse-submenu">
                <a href="{{ route_if_exists('timetables.class-scheduling') }}" class="nav-link {{ request()->routeIs('timetables.class-scheduling') ? 'active' : '' }}"><span class="link-text">Class Scheduling</span></a>
                <a href="{{ route_if_exists('timetables.teacher-allocation') }}" class="nav-link {{ request()->routeIs('timetables.teacher-allocation') ? 'active' : '' }}"><span class="link-text">Teacher Allocation</span></a>
                <a href="{{ route_if_exists('timetables.subject-allocation') }}" class="nav-link {{ request()->routeIs('timetables.subject-allocation') ? 'active' : '' }}"><span class="link-text">Subject Allocation</span></a>
                <a href="{{ route_if_exists('timetables.room-allocation') }}" class="nav-link {{ request()->routeIs('timetables.room-allocation') ? 'active' : '' }}"><span class="link-text">Room Allocation</span></a>
                <a href="{{ route_if_exists('timetables.conflict-detection') }}" class="nav-link {{ request()->routeIs('timetables.conflict-detection') ? 'active' : '' }}"><span class="link-text">Conflict Detection</span></a>
                <a href="{{ route_if_exists('timetables.ai-generator') }}" class="nav-link {{ request()->routeIs('timetables.ai-generator') ? 'active' : '' }}"><span class="link-text">AI Timetable Generator</span></a>
                <a href="{{ route_if_exists('timetable.substitutions') }}" class="nav-link {{ request()->routeIs('timetable.substitutions*') ? 'active' : '' }}"><span class="link-text">Substitution Management</span></a>
                <a href="{{ route_if_exists('timetables.exam-timetable') }}" class="nav-link {{ request()->routeIs('timetables.exam-timetable') ? 'active' : '' }}"><span class="link-text">Exam Timetable</span></a>
                <a href="{{ route_if_exists('timetables.timetable-reports') }}" class="nav-link {{ request()->routeIs('timetables.timetable-reports') ? 'active' : '' }}"><span class="link-text">Timetable Reports</span></a>
            </div>
        </div>
    </div>

    <div class="nav-category">Assessments</div>
    <div class="nav-item">
        <a class="nav-link has-submenu" data-bs-toggle="collapse" href="#assessmentMenu" role="button" aria-expanded="false">
            <i class="fas fa-clipboard-check"></i><span class="link-text">Assessments</span>
        </a>
        <div class="collapse" id="assessmentMenu">
            <div class="collapse-submenu">
                <a href="{{ route_if_exists('assessment.exam-setup') }}" class="nav-link {{ request()->routeIs('assessment.exam-setup') ? 'active' : '' }}"><span class="link-text">Exam Setup</span></a>
                <a href="{{ route_if_exists('assessment.grading-system') }}" class="nav-link {{ request()->routeIs('assessment.grading-system') ? 'active' : '' }}"><span class="link-text">Grading System</span></a>
                <a href="{{ route_if_exists('assessment.subject-marks') }}" class="nav-link {{ request()->routeIs('assessment.subject-marks') ? 'active' : '' }}"><span class="link-text">Subject Marks</span></a>
                <a href="{{ route_if_exists('assessment.practical-marks') }}" class="nav-link {{ request()->routeIs('assessment.practical-marks') ? 'active' : '' }}"><span class="link-text">Practical Marks</span></a>
                <a href="{{ route_if_exists('assessment.assignment-marks') }}" class="nav-link {{ request()->routeIs('assessment.assignment-marks') ? 'active' : '' }}"><span class="link-text">Assignment Marks</span></a>
                <a href="{{ route_if_exists('assessment.continuous-assessment') }}" class="nav-link {{ request()->routeIs('assessment.continuous-assessment') ? 'active' : '' }}"><span class="link-text">Continuous Assessment</span></a>
                <a href="{{ route_if_exists('assessment.online-examination') }}" class="nav-link {{ request()->routeIs('assessment.online-examination') ? 'active' : '' }}"><span class="link-text">Online Examination</span></a>
                <a href="{{ route_if_exists('assessment.ai-evaluation') }}" class="nav-link {{ request()->routeIs('assessment.ai-evaluation') ? 'active' : '' }}"><span class="link-text">AI Evaluation Support</span></a>
                <a href="{{ route_if_exists('assessment.result-processing') }}" class="nav-link {{ request()->routeIs('assessment.result-processing') ? 'active' : '' }}"><span class="link-text">Result Processing</span></a>
                <a href="{{ route_if_exists('assessment.ranking') }}" class="nav-link {{ request()->routeIs('assessment.ranking') ? 'active' : '' }}"><span class="link-text">Ranking</span></a>
                <a href="{{ route_if_exists('assessment.performance-analytics') }}" class="nav-link {{ request()->routeIs('assessment.performance-analytics') ? 'active' : '' }}"><span class="link-text">Performance Analytics</span></a>
            </div>
        </div>
    </div>

    <div class="nav-category">HR & Payroll</div>
    <div class="nav-item">
        <a class="nav-link has-submenu" data-bs-toggle="collapse" href="#hrMenu" role="button" aria-expanded="false">
            <i class="fas fa-users"></i><span class="link-text">HR</span>
        </a>
        <div class="collapse" id="hrMenu">
            <div class="collapse-submenu">
                <a href="{{ route_if_exists('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}"><span class="link-text">Employees</span></a>
                <a href="{{ route_if_exists('hr.documents') }}" class="nav-link {{ request()->routeIs('hr.documents') ? 'active' : '' }}"><span class="link-text">Employee Documents</span></a>
                <a href="{{ route_if_exists('hr.evaluations') }}" class="nav-link {{ request()->routeIs('hr.evaluations') ? 'active' : '' }}"><span class="link-text">Employee Evaluation</span></a>
                <a href="{{ route_if_exists('hr.transfers') }}" class="nav-link {{ request()->routeIs('hr.transfers') ? 'active' : '' }}"><span class="link-text">Employee Transfer</span></a>
                <a href="{{ route_if_exists('hr.promotions') }}" class="nav-link {{ request()->routeIs('hr.promotions') ? 'active' : '' }}"><span class="link-text">Employee Promotion</span></a>
                <a href="{{ route_if_exists('hr.directory') }}" class="nav-link {{ request()->routeIs('hr.directory') ? 'active' : '' }}"><span class="link-text">Staff Directory</span></a>
                <a href="{{ route_if_exists('hr.reports') }}" class="nav-link {{ request()->routeIs('hr.reports') ? 'active' : '' }}"><span class="link-text">Employee Reports</span></a>
                <a href="{{ url('/departments') }}" class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}"><span class="link-text">Departments</span></a>
                <a href="{{ route_if_exists('designations.index') }}" class="nav-link {{ request()->routeIs('designations.*') ? 'active' : '' }}"><span class="link-text">Designations</span></a>
                <a href="{{ route_if_exists('hr.recruitment') }}" class="nav-link {{ request()->routeIs('hr.recruitment*') ? 'active' : '' }}"><span class="link-text">Recruitment</span></a>
            </div>
        </div>
    </div>
    <div class="nav-item">
        <a class="nav-link has-submenu" data-bs-toggle="collapse" href="#payrollMenu" role="button" aria-expanded="false">
            <i class="fas fa-wallet"></i><span class="link-text">Payroll</span>
        </a>
        <div class="collapse" id="payrollMenu">
            <div class="collapse-submenu">
                <a href="{{ route_if_exists('payroll.index') }}" class="nav-link {{ request()->routeIs('payroll.index') ? 'active' : '' }}"><span class="link-text">Salary Structures</span></a>
                <a href="{{ route_if_exists('payroll.salary-components') }}" class="nav-link {{ request()->routeIs('payroll.salary-components') ? 'active' : '' }}"><span class="link-text">Salary Components</span></a>
                <a href="{{ route_if_exists('payroll.processing') }}" class="nav-link {{ request()->routeIs('payroll.processing') ? 'active' : '' }}"><span class="link-text">Payroll Processing</span></a>
                <a href="{{ route_if_exists('payroll.loans') }}" class="nav-link {{ request()->routeIs('payroll.loans') ? 'active' : '' }}"><span class="link-text">Loans</span></a>
                <a href="{{ route_if_exists('payroll.overtime') }}" class="nav-link {{ request()->routeIs('payroll.overtime') ? 'active' : '' }}"><span class="link-text">Overtime</span></a>
                <a href="{{ route_if_exists('payroll.tax-management') }}" class="nav-link {{ request()->routeIs('payroll.tax-management') ? 'active' : '' }}"><span class="link-text">Tax Management</span></a>
                <a href="{{ route_if_exists('payroll.bonus-management') }}" class="nav-link {{ request()->routeIs('payroll.bonus-management') ? 'active' : '' }}"><span class="link-text">Bonus Management</span></a>
                <a href="{{ route_if_exists('payroll.reports') }}" class="nav-link {{ request()->routeIs('payroll.reports') ? 'active' : '' }}"><span class="link-text">Payroll Reports</span></a>
                <a href="{{ route_if_exists('payroll.tally-export') }}" class="nav-link {{ request()->routeIs('payroll.tally-export') ? 'active' : '' }}"><span class="link-text">Tally Export</span></a>
            </div>
        </div>
    </div>

    <div class="nav-item">
        <a class="nav-link has-submenu" data-bs-toggle="collapse" href="#empLeaveMenu" role="button" aria-expanded="false">
            <i class="fas fa-calendar-alt"></i><span class="link-text">Employee Leave Management</span>
        </a>
        <div class="collapse" id="empLeaveMenu">
            <div class="collapse-submenu">
                <a href="{{ route_if_exists('hr.employee-leave.policies') }}" class="nav-link {{ request()->routeIs('hr.employee-leave.policies') ? 'active' : '' }}"><span class="link-text">Leave Policies</span></a>
                <a href="{{ route_if_exists('hr.employee-leave.types') }}" class="nav-link {{ request()->routeIs('hr.employee-leave.types') ? 'active' : '' }}"><span class="link-text">Leave Types</span></a>
                <a href="{{ route_if_exists('hr.employee-leave.requests') }}" class="nav-link {{ request()->routeIs('hr.employee-leave.requests') ? 'active' : '' }}"><span class="link-text">Leave Requests</span></a>
                <a href="{{ route_if_exists('hr.employee-leave.approval-workflows') }}" class="nav-link {{ request()->routeIs('hr.employee-leave.approval-workflows') ? 'active' : '' }}"><span class="link-text">Approval Workflow</span></a>
                <a href="{{ route_if_exists('hr.employee-leave.balances') }}" class="nav-link {{ request()->routeIs('hr.employee-leave.balances') ? 'active' : '' }}"><span class="link-text">Leave Balance</span></a>
                <a href="{{ route_if_exists('hr.employee-leave.encashments') }}" class="nav-link {{ request()->routeIs('hr.employee-leave.encashments') ? 'active' : '' }}"><span class="link-text">Leave Encashment</span></a>
                <a href="{{ route_if_exists('hr.employee-leave.holiday-calendar') }}" class="nav-link {{ request()->routeIs('hr.employee-leave.holiday-calendar') ? 'active' : '' }}"><span class="link-text">Holiday Calendar</span></a>
                <a href="{{ route_if_exists('hr.employee-leave.reports') }}" class="nav-link {{ request()->routeIs('hr.employee-leave.reports') ? 'active' : '' }}"><span class="link-text">Reports</span></a>
            </div>
        </div>
    </div>

    <div class="nav-category">Services</div>
    <div class="nav-item">
        <a class="nav-link has-submenu" data-bs-toggle="collapse" href="#transportMenu" role="button" aria-expanded="false">
            <i class="fas fa-bus"></i><span class="link-text">Transport</span>
        </a>
        <div class="collapse" id="transportMenu">
            <div class="collapse-submenu">
                <a href="{{ route_if_exists('transport.routes') }}" class="nav-link {{ request()->routeIs('transport.routes*') ? 'active' : '' }}"><span class="link-text">Routes</span></a>
                <a href="{{ route_if_exists('transport.vehicles') }}" class="nav-link {{ request()->routeIs('transport.vehicles*') ? 'active' : '' }}"><span class="link-text">Vehicles</span></a>
                <a href="{{ route_if_exists('transport.drivers') }}" class="nav-link {{ request()->routeIs('transport.drivers*') ? 'active' : '' }}"><span class="link-text">Drivers</span></a>
                <a href="{{ route_if_exists('transport.allocations') }}" class="nav-link {{ request()->routeIs('transport.allocations*') ? 'active' : '' }}"><span class="link-text">Allocations</span></a>
            </div>
        </div>
    </div>
    <div class="nav-item">
        <a class="nav-link has-submenu" data-bs-toggle="collapse" href="#libraryMenu" role="button" aria-expanded="false">
            <i class="fas fa-book"></i><span class="link-text">Library</span>
        </a>
        <div class="collapse" id="libraryMenu">
            <div class="collapse-submenu">
                <a href="{{ url('/books') }}" class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}"><span class="link-text">Books</span></a>
                <a href="{{ url('/book-issues') }}" class="nav-link {{ request()->routeIs('book-issues.*') ? 'active' : '' }}"><span class="link-text">Issues</span></a>
                <a href="{{ route_if_exists('library.members') }}" class="nav-link {{ request()->routeIs('library.members') ? 'active' : '' }}"><span class="link-text">Members</span></a>
                <a href="{{ route_if_exists('library.barcode') }}" class="nav-link {{ request()->routeIs('library.barcode') ? 'active' : '' }}"><span class="link-text">Barcode</span></a>
                <a href="{{ route_if_exists('library.return') }}" class="nav-link {{ request()->routeIs('library.return') ? 'active' : '' }}"><span class="link-text">Return</span></a>
                <a href="{{ route_if_exists('library.fines') }}" class="nav-link {{ request()->routeIs('library.fines') ? 'active' : '' }}"><span class="link-text">Fine</span></a>
                <a href="{{ route_if_exists('library.ebook') }}" class="nav-link {{ request()->routeIs('library.ebook') ? 'active' : '' }}"><span class="link-text">E-Book</span></a>
                <a href="{{ route_if_exists('library.reports') }}" class="nav-link {{ request()->routeIs('library.reports') ? 'active' : '' }}"><span class="link-text">Reports</span></a>
            </div>
        </div>
    </div>
    <div class="nav-item">
        <a class="nav-link has-submenu" data-bs-toggle="collapse" href="#inventoryMenu" role="button" aria-expanded="false">
            <i class="fas fa-boxes"></i><span class="link-text">Inventory</span>
        </a>
        <div class="collapse" id="inventoryMenu">
            <div class="collapse-submenu">
                <a href="{{ route_if_exists('inventory.items') }}" class="nav-link {{ request()->routeIs('inventory.items*') ? 'active' : '' }}"><span class="link-text">Item</span></a>
                <a href="{{ route_if_exists('inventory.categories') }}" class="nav-link {{ request()->routeIs('inventory.categories*') ? 'active' : '' }}"><span class="link-text">Category</span></a>
                <a href="{{ route_if_exists('inventory.stock') }}" class="nav-link {{ request()->routeIs('inventory.stock*') ? 'active' : '' }}"><span class="link-text">Stock</span></a>
                <a href="{{ route_if_exists('inventory.purchaseOrders') }}" class="nav-link {{ request()->routeIs('inventory.purchaseOrders*') ? 'active' : '' }}"><span class="link-text">Purchase Orders</span></a>
                <a href="{{ route_if_exists('inventory.vendors') }}" class="nav-link {{ request()->routeIs('inventory.vendors*') ? 'active' : '' }}"><span class="link-text">Vendor</span></a>
                <a href="{{ route_if_exists('inventory.stock-transfers') }}" class="nav-link {{ request()->routeIs('inventory.stock-transfers*') ? 'active' : '' }}"><span class="link-text">Stock Transfers</span></a>
                <a href="{{ route_if_exists('inventory.stock-audit') }}" class="nav-link {{ request()->routeIs('inventory.stock-audit*') ? 'active' : '' }}"><span class="link-text">Stock Audit</span></a>
                <a href="{{ route_if_exists('inventory.barcode') }}" class="nav-link {{ request()->routeIs('inventory.barcode*') ? 'active' : '' }}"><span class="link-text">Barcode</span></a>
                <a href="{{ route_if_exists('inventory.report') }}" class="nav-link {{ request()->routeIs('inventory.report*') ? 'active' : '' }}"><span class="link-text">Report</span></a>
            </div>
        </div>
    </div>

    <div class="nav-category">Other Modules</div>
    <div class="nav-item">
        <a class="nav-link has-submenu" data-bs-toggle="collapse" href="#eventsMenu" role="button" aria-expanded="false">
            <i class="fas fa-calendar-alt"></i><span class="link-text">Events & Clubs</span>
        </a>
        <div class="collapse" id="eventsMenu">
            <div class="collapse-submenu">
                <a href="{{ route_if_exists('events.index') }}" class="nav-link {{ request()->routeIs('events.index') ? 'active' : '' }}"><span class="link-text">School Events</span></a>
                <a href="{{ route_if_exists('events.competitions') }}" class="nav-link {{ request()->routeIs('events.competitions') ? 'active' : '' }}"><span class="link-text">Competitions</span></a>
                <a href="{{ route_if_exists('events.workshops') }}" class="nav-link {{ request()->routeIs('events.workshops') ? 'active' : '' }}"><span class="link-text">Workshops</span></a>
                <a href="{{ route_if_exists('events.sports') }}" class="nav-link {{ request()->routeIs('events.sports') ? 'active' : '' }}"><span class="link-text">Sports Activities</span></a>
                <a href="{{ route_if_exists('events.clubs') }}" class="nav-link {{ request()->routeIs('events.clubs*') ? 'active' : '' }}"><span class="link-text">Clubs</span></a>
                <a href="{{ route_if_exists('events.calendar') }}" class="nav-link {{ request()->routeIs('events.calendar') ? 'active' : '' }}"><span class="link-text">Calendar Management</span></a>
                <a href="{{ route_if_exists('events.registration') }}" class="nav-link {{ request()->routeIs('events.registration*') ? 'active' : '' }}"><span class="link-text">Event Registration</span></a>
                <a href="{{ route_if_exists('events.attendance') }}" class="nav-link {{ request()->routeIs('events.attendance*') ? 'active' : '' }}"><span class="link-text">Event Attendance</span></a>
                <a href="{{ route_if_exists('events.reports') }}" class="nav-link {{ request()->routeIs('events.reports') ? 'active' : '' }}"><span class="link-text">Event Reports</span></a>
                <hr class="my-1 mx-3">
                <a href="{{ route_if_exists('front-office.dashboard') }}" class="nav-link {{ request()->routeIs('front-office.dashboard') ? 'active' : '' }}"><span class="link-text">Reception Dashboard</span></a>
                <a href="{{ route_if_exists('front-office.index') }}" class="nav-link {{ request()->routeIs('front-office.index') ? 'active' : '' }}"><span class="link-text">Visitor Management</span></a>
                <a href="{{ route_if_exists('front-office.enquiries') }}" class="nav-link {{ request()->routeIs('front-office.enquiries*') ? 'active' : '' }}"><span class="link-text">Enquiry Management</span></a>
                <a href="{{ route_if_exists('front-office.call-logs') }}" class="nav-link {{ request()->routeIs('front-office.call-logs*') ? 'active' : '' }}"><span class="link-text">Call Log</span></a>
                <a href="{{ route_if_exists('front-office.appointments') }}" class="nav-link {{ request()->routeIs('front-office.appointments*') ? 'active' : '' }}"><span class="link-text">Appointment Scheduling</span></a>
                <a href="{{ route_if_exists('front-office.complaints') }}" class="nav-link {{ request()->routeIs('front-office.complaints*') ? 'active' : '' }}"><span class="link-text">Complaint Management</span></a>
            </div>
        </div>
    </div>
    <div class="nav-item">
        <a class="nav-link has-submenu" data-bs-toggle="collapse" href="#healthMenu" role="button" aria-expanded="false">
            <i class="fas fa-heartbeat"></i><span class="link-text">Health Records</span>
        </a>
        <div class="collapse" id="healthMenu">
            <div class="collapse-submenu">
                <a href="{{ route_if_exists('health.student-records') }}" class="nav-link {{ request()->routeIs('health.student-records*') ? 'active' : '' }}"><span class="link-text">Student Health Records</span></a>
                <a href="{{ route_if_exists('health.vaccinations') }}" class="nav-link {{ request()->routeIs('health.vaccinations*') ? 'active' : '' }}"><span class="link-text">Vaccination Records</span></a>
                <a href="{{ route_if_exists('health.medical-history') }}" class="nav-link {{ request()->routeIs('health.medical-history*') ? 'active' : '' }}"><span class="link-text">Medical History</span></a>
                <a href="{{ route_if_exists('health.checkups') }}" class="nav-link {{ request()->routeIs('health.checkups*') ? 'active' : '' }}"><span class="link-text">Health Checkups</span></a>
                <a href="{{ route_if_exists('health.medicines') }}" class="nav-link {{ request()->routeIs('health.medicines*') ? 'active' : '' }}"><span class="link-text">Medicine Tracking</span></a>
                <a href="{{ route_if_exists('health.emergency-contacts') }}" class="nav-link {{ request()->routeIs('health.emergency-contacts*') ? 'active' : '' }}"><span class="link-text">Emergency Contacts</span></a>
                <a href="{{ route_if_exists('health.reports') }}" class="nav-link {{ request()->routeIs('health.reports*') ? 'active' : '' }}"><span class="link-text">Health Reports</span></a>
            </div>
        </div>
    </div>
    <div class="nav-item">
        <a class="nav-link has-submenu" data-bs-toggle="collapse" href="#certificateMenu" role="button" aria-expanded="false">
            <i class="fas fa-certificate"></i><span class="link-text">Certificates</span>
        </a>
        <div class="collapse" id="certificateMenu">
            <div class="collapse-submenu">
                <a href="{{ route_if_exists('certificates.transfer') }}" class="nav-link {{ request()->routeIs('certificates.transfer*') ? 'active' : '' }}"><span class="link-text">Transfer Certificate</span></a>
                <a href="{{ route_if_exists('certificates.character') }}" class="nav-link {{ request()->routeIs('certificates.character*') ? 'active' : '' }}"><span class="link-text">Character Certificate</span></a>
                <a href="{{ route_if_exists('certificates.bonafide') }}" class="nav-link {{ request()->routeIs('certificates.bonafide*') ? 'active' : '' }}"><span class="link-text">Bonafide Certificate</span></a>
                <a href="{{ route_if_exists('certificates.course-completion') }}" class="nav-link {{ request()->routeIs('certificates.course-completion*') ? 'active' : '' }}"><span class="link-text">Course Completion</span></a>
                <a href="{{ route_if_exists('certificates.custom') }}" class="nav-link {{ request()->routeIs('certificates.custom*') ? 'active' : '' }}"><span class="link-text">Custom Certificates</span></a>
                <a href="{{ route_if_exists('certificates.qr-verify') }}" class="nav-link {{ request()->routeIs('certificates.qr-verify*') ? 'active' : '' }}"><span class="link-text">QR Verification</span></a>
                <a href="{{ route_if_exists('certificates.digital-signature') }}" class="nav-link {{ request()->routeIs('certificates.digital-signature*') ? 'active' : '' }}"><span class="link-text">Digital Signature</span></a>
                <a href="{{ route_if_exists('certificates.templates') }}" class="nav-link {{ request()->routeIs('certificates.templates*') ? 'active' : '' }}"><span class="link-text">Certificate Templates</span></a>
            </div>
        </div>
    </div>
    <div class="nav-item">
        <a class="nav-link has-submenu" data-bs-toggle="collapse" href="#misMenu" role="button" aria-expanded="false">
            <i class="fas fa-chart-bar"></i><span class="link-text">MIS Reports</span>
        </a>
        <div class="collapse" id="misMenu">
            <div class="collapse-submenu">
                <a href="{{ route_if_exists('mis.executive-dashboard') }}" class="nav-link {{ request()->routeIs('mis.executive-dashboard*') ? 'active' : '' }}"><span class="link-text">Executive Dashboard</span></a>
                <a href="{{ route_if_exists('mis.kpi-tracking') }}" class="nav-link {{ request()->routeIs('mis.kpi-tracking*') ? 'active' : '' }}"><span class="link-text">KPI Tracking</span></a>
                <a href="{{ route_if_exists('mis.academic-analytics') }}" class="nav-link {{ request()->routeIs('mis.academic-analytics*') ? 'active' : '' }}"><span class="link-text">Academic Analytics</span></a>
                <a href="{{ route_if_exists('mis.financial-analytics') }}" class="nav-link {{ request()->routeIs('mis.financial-analytics*') ? 'active' : '' }}"><span class="link-text">Financial Analytics</span></a>
                <a href="{{ route_if_exists('mis.student-analytics') }}" class="nav-link {{ request()->routeIs('mis.student-analytics*') ? 'active' : '' }}"><span class="link-text">Student Analytics</span></a>
                <a href="{{ route_if_exists('mis.attendance-analytics') }}" class="nav-link {{ request()->routeIs('mis.attendance-analytics*') ? 'active' : '' }}"><span class="link-text">Attendance Analytics</span></a>
                <a href="{{ route_if_exists('mis.ai-predictive-analytics') }}" class="nav-link {{ request()->routeIs('mis.ai-predictive-analytics*') ? 'active' : '' }}"><span class="link-text">AI Predictive Analytics</span></a>
                <a href="{{ route_if_exists('mis.custom-reports') }}" class="nav-link {{ request()->routeIs('mis.custom-reports*') ? 'active' : '' }}"><span class="link-text">Custom Reports</span></a>
            </div>
        </div>
    </div>
    <div class="nav-item">
        <a class="nav-link has-submenu" data-bs-toggle="collapse" href="#accountingMenu" role="button" aria-expanded="false">
            <i class="fas fa-calculator"></i><span class="link-text">Accounting</span>
        </a>
        <div class="collapse" id="accountingMenu">
            <div class="collapse-submenu">
                <a href="{{ route_if_exists('accounting.chart-of-accounts') }}" class="nav-link {{ request()->routeIs('accounting.chart-of-accounts') ? 'active' : '' }}"><span class="link-text">Chart of Accounts</span></a>
                <a href="{{ route_if_exists('accounting.general-ledger') }}" class="nav-link {{ request()->routeIs('accounting.general-ledger') ? 'active' : '' }}"><span class="link-text">General Ledger</span></a>
                <a href="{{ route_if_exists('accounting.journal-entry') }}" class="nav-link {{ request()->routeIs('accounting.journal-entry') ? 'active' : '' }}"><span class="link-text">Journal Entry</span></a>
                <a href="{{ route_if_exists('accounting.payable') }}" class="nav-link {{ request()->routeIs('accounting.payable') ? 'active' : '' }}"><span class="link-text">Accounts Payable</span></a>
                <a href="{{ route_if_exists('accounting.receivable') }}" class="nav-link {{ request()->routeIs('accounting.receivable') ? 'active' : '' }}"><span class="link-text">Accounts Receivable</span></a>
                <a href="{{ route_if_exists('accounting.trial-balance') }}" class="nav-link {{ request()->routeIs('accounting.trial-balance') ? 'active' : '' }}"><span class="link-text">Trial Balance</span></a>
                <a href="{{ route_if_exists('accounting.cash-book') }}" class="nav-link {{ request()->routeIs('accounting.cash-book') ? 'active' : '' }}"><span class="link-text">Cash Book</span></a>
                <a href="{{ route_if_exists('accounting.bank-reconciliation') }}" class="nav-link {{ request()->routeIs('accounting.bank-reconciliation') ? 'active' : '' }}"><span class="link-text">Bank Reconciliation</span></a>
                <a href="{{ route_if_exists('accounting.budget') }}" class="nav-link {{ request()->routeIs('accounting.budget') ? 'active' : '' }}"><span class="link-text">Budget Management</span></a>
                <a href="{{ route_if_exists('accounting.statements') }}" class="nav-link {{ request()->routeIs('accounting.statements') ? 'active' : '' }}"><span class="link-text">Financial Statements</span></a>
                <a href="{{ route_if_exists('accounting.currencies') }}" class="nav-link {{ request()->routeIs('accounting.currencies') ? 'active' : '' }}"><span class="link-text">Currencies</span></a>
                <a href="{{ route_if_exists('accounting.exchange-rates') }}" class="nav-link {{ request()->routeIs('accounting.exchange-rates') ? 'active' : '' }}"><span class="link-text">Exchange Rates</span></a>
                <a href="{{ route_if_exists('accounting.report') }}" class="nav-link {{ request()->routeIs('accounting.report') ? 'active' : '' }}"><span class="link-text">Report</span></a>
            </div>
        </div>
    </div>
    <div class="nav-item">
        <a class="nav-link has-submenu" data-bs-toggle="collapse" href="#hostelMenu" role="button" aria-expanded="false">
            <i class="fas fa-bed"></i><span class="link-text">Hostel</span>
        </a>
        <div class="collapse" id="hostelMenu">
            <div class="collapse-submenu">
                <a href="{{ route_if_exists('hostels.index') }}" class="nav-link {{ request()->routeIs('hostels.index') ? 'active' : '' }}"><span class="link-text">Hostel Setup</span></a>
                <a href="{{ route_if_exists('hostels.allocations') }}" class="nav-link {{ request()->routeIs('hostels.allocations*') ? 'active' : '' }}"><span class="link-text">Room Allocation</span></a>
                <a href="{{ route_if_exists('hostels.beds') }}" class="nav-link {{ request()->routeIs('hostels.beds*') ? 'active' : '' }}"><span class="link-text">Bed Management</span></a>
                <a href="{{ route_if_exists('hostels.fees') }}" class="nav-link {{ request()->routeIs('hostels.fees*') ? 'active' : '' }}"><span class="link-text">Hostel Fees</span></a>
                <a href="{{ route_if_exists('hostels.visitors') }}" class="nav-link {{ request()->routeIs('hostels.visitors*') ? 'active' : '' }}"><span class="link-text">Visitor Tracking</span></a>
                <a href="{{ route_if_exists('hostels.leaves') }}" class="nav-link {{ request()->routeIs('hostels.leaves*') ? 'active' : '' }}"><span class="link-text">Leave Management</span></a>
                <a href="{{ route_if_exists('hostels.reports') }}" class="nav-link {{ request()->routeIs('hostels.reports*') ? 'active' : '' }}"><span class="link-text">Hostel Reports</span></a>
            </div>
        </div>
    </div>
    <div class="nav-item">
        <a class="nav-link has-submenu" data-bs-toggle="collapse" href="#alumniMenu" role="button" aria-expanded="false">
            <i class="fas fa-graduation-cap"></i><span class="link-text">Alumni</span>
        </a>
        <div class="collapse" id="alumniMenu">
            <div class="collapse-submenu">
                <a href="{{ route_if_exists('alumni.index') }}" class="nav-link {{ request()->routeIs('alumni.index') ? 'active' : '' }}"><span class="link-text">Directory</span></a>
                <a href="{{ route_if_exists('alumni.portal') }}" class="nav-link {{ request()->routeIs('alumni.portal') ? 'active' : '' }}"><span class="link-text">Portal</span></a>
                <a href="{{ route_if_exists('alumni.events') }}" class="nav-link {{ request()->routeIs('alumni.events*') ? 'active' : '' }}"><span class="link-text">Events</span></a>
                <a href="{{ route_if_exists('alumni.donations') }}" class="nav-link {{ request()->routeIs('alumni.donations*') ? 'active' : '' }}"><span class="link-text">Donations</span></a>
                <a href="{{ route_if_exists('alumni.jobs') }}" class="nav-link {{ request()->routeIs('alumni.jobs*') ? 'active' : '' }}"><span class="link-text">Job Board</span></a>
                <a href="{{ route_if_exists('alumni.networking') }}" class="nav-link {{ request()->routeIs('alumni.networking*') ? 'active' : '' }}"><span class="link-text">Networking</span></a>
                <a href="{{ route_if_exists('alumni.reports') }}" class="nav-link {{ request()->routeIs('alumni.reports*') ? 'active' : '' }}"><span class="link-text">Reports</span></a>
            </div>
        </div>
    </div>
    <div class="nav-item">
        <a class="nav-link has-submenu" data-bs-toggle="collapse" href="#assetMenu" role="button" aria-expanded="false">
            <i class="fas fa-building"></i><span class="link-text">Asset Management</span>
        </a>
        <div class="collapse" id="assetMenu">
            <div class="collapse-submenu">
                <a href="{{ route_if_exists('asset.index') }}" class="nav-link {{ request()->routeIs('asset.index') ? 'active' : '' }}"><span class="link-text">Asset Registration</span></a>
                <a href="{{ route_if_exists('asset.tagging') }}" class="nav-link {{ request()->routeIs('asset.tagging*') ? 'active' : '' }}"><span class="link-text">Asset Tagging</span></a>
                <a href="{{ route_if_exists('asset.barcode-tracking') }}" class="nav-link {{ request()->routeIs('asset.barcode-tracking*') ? 'active' : '' }}"><span class="link-text">Barcode Tracking</span></a>
                <a href="{{ route_if_exists('asset.allocations') }}" class="nav-link {{ request()->routeIs('asset.allocations*') ? 'active' : '' }}"><span class="link-text">Asset Allocation</span></a>
                <a href="{{ route_if_exists('asset.maintenance') }}" class="nav-link {{ request()->routeIs('asset.maintenance*') ? 'active' : '' }}"><span class="link-text">Asset Maintenance</span></a>
                <a href="{{ route_if_exists('asset.depreciation') }}" class="nav-link {{ request()->routeIs('asset.depreciation*') ? 'active' : '' }}"><span class="link-text">Depreciation</span></a>
                <a href="{{ route_if_exists('asset.audit') }}" class="nav-link {{ request()->routeIs('asset.audit*') ? 'active' : '' }}"><span class="link-text">Asset Audit</span></a>
                <a href="{{ route_if_exists('asset.reports') }}" class="nav-link {{ request()->routeIs('asset.reports*') ? 'active' : '' }}"><span class="link-text">Asset Reports</span></a>
            </div>
        </div>
    </div>

    <div class="nav-category">AI Integration</div>
    <div class="nav-item">
        <a class="nav-link has-submenu" data-bs-toggle="collapse" href="#aiMenu" role="button" aria-expanded="false">
            <i class="fas fa-robot"></i><span class="link-text">AI Integration</span>
        </a>
        <div class="collapse" id="aiMenu">
            <div class="collapse-submenu">
                <a href="{{ route_if_exists('ai.chat') }}" class="nav-link {{ request()->routeIs('ai.chat') ? 'active' : '' }}"><span class="link-text">AI Chat Assistant</span></a>
                <a href="{{ route_if_exists('ai.performance-prediction') }}" class="nav-link {{ request()->routeIs('ai.performance-prediction') ? 'active' : '' }}"><span class="link-text">Student Performance Prediction</span></a>
                <a href="{{ route_if_exists('ai.attendance-prediction') }}" class="nav-link {{ request()->routeIs('ai.attendance-prediction') ? 'active' : '' }}"><span class="link-text">Attendance Prediction</span></a>
                <a href="{{ route_if_exists('ai.fee-defaulter-prediction') }}" class="nav-link {{ request()->routeIs('ai.fee-defaulter-prediction') ? 'active' : '' }}"><span class="link-text">Fee Defaulter Prediction</span></a>
                <a href="{{ route_if_exists('ai.report-generator') }}" class="nav-link {{ request()->routeIs('ai.report-generator') ? 'active' : '' }}"><span class="link-text">AI Report Generator</span></a>
                <a href="{{ route_if_exists('ai.timetable-generator') }}" class="nav-link {{ request()->routeIs('ai.timetable-generator') ? 'active' : '' }}"><span class="link-text">AI Timetable Generator</span></a>
                <a href="{{ route_if_exists('ai.analytics-dashboard') }}" class="nav-link {{ request()->routeIs('ai.analytics-dashboard') ? 'active' : '' }}"><span class="link-text">AI Analytics Dashboard</span></a>
                <a href="{{ route_if_exists('ai.recommendation-engine') }}" class="nav-link {{ request()->routeIs('ai.recommendation-engine') ? 'active' : '' }}"><span class="link-text">AI Recommendation Engine</span></a>
            </div>
        </div>
    </div>

    <div class="nav-category">System</div>
    <div class="nav-item">
        <a class="nav-link has-submenu" data-bs-toggle="collapse" href="#generalSettingsMenu" role="button" aria-expanded="false">
            <i class="fas fa-cog"></i><span class="link-text">General Settings</span>
        </a>
        <div class="collapse" id="generalSettingsMenu">
            <div class="collapse-submenu">
                <a href="{{ route_if_exists('settings.school') }}" class="nav-link {{ request()->routeIs('settings.school') ? 'active' : '' }}"><span class="link-text">School Settings</span></a>
                <a href="{{ route_if_exists('settings.academicYears') }}" class="nav-link {{ request()->routeIs('settings.academicYears') ? 'active' : '' }}"><span class="link-text">Academic Years</span></a>
                <hr class="my-1 mx-3">
                <a href="{{ route_if_exists('homepage.hero') }}" class="nav-link {{ request()->routeIs('homepage.hero') ? 'active' : '' }}"><span class="link-text">Hero / Banner Section</span></a>
                <a href="{{ route_if_exists('homepage.navigation') }}" class="nav-link {{ request()->routeIs('homepage.navigation') ? 'active' : '' }}"><span class="link-text">Navigation Settings</span></a>
                <a href="{{ route_if_exists('homepage.about') }}" class="nav-link {{ request()->routeIs('homepage.about') ? 'active' : '' }}"><span class="link-text">About Section</span></a>
                <a href="{{ route_if_exists('homepage.services') }}" class="nav-link {{ request()->routeIs('homepage.services') ? 'active' : '' }}"><span class="link-text">Services Section</span></a>
                <a href="{{ route_if_exists('homepage.features') }}" class="nav-link {{ request()->routeIs('homepage.features') ? 'active' : '' }}"><span class="link-text">Features Section</span></a>
                <a href="{{ route_if_exists('homepage.products') }}" class="nav-link {{ request()->routeIs('homepage.products') ? 'active' : '' }}"><span class="link-text">Products Section</span></a>
                <a href="{{ route_if_exists('homepage.portfolio') }}" class="nav-link {{ request()->routeIs('homepage.portfolio') ? 'active' : '' }}"><span class="link-text">Portfolio / Projects</span></a>
                <a href="{{ route_if_exists('homepage.testimonials') }}" class="nav-link {{ request()->routeIs('homepage.testimonials') ? 'active' : '' }}"><span class="link-text">Testimonials</span></a>
                <a href="{{ route_if_exists('homepage.team') }}" class="nav-link {{ request()->routeIs('homepage.team') ? 'active' : '' }}"><span class="link-text">Team Section</span></a>
                <a href="{{ route_if_exists('homepage.statistics') }}" class="nav-link {{ request()->routeIs('homepage.statistics') ? 'active' : '' }}"><span class="link-text">Statistics / Counters</span></a>
                <a href="{{ route_if_exists('homepage.video') }}" class="nav-link {{ request()->routeIs('homepage.video') ? 'active' : '' }}"><span class="link-text">Video Section</span></a>
                <a href="{{ route_if_exists('homepage.faq') }}" class="nav-link {{ request()->routeIs('homepage.faq') ? 'active' : '' }}"><span class="link-text">FAQ Section</span></a>
                <a href="{{ route_if_exists('homepage.pricing') }}" class="nav-link {{ request()->routeIs('homepage.pricing') ? 'active' : '' }}"><span class="link-text">Pricing Plans</span></a>
                <a href="{{ route_if_exists('homepage.blog') }}" class="nav-link {{ request()->routeIs('homepage.blog') ? 'active' : '' }}"><span class="link-text">Blog Section</span></a>
                <a href="{{ route_if_exists('homepage.cta') }}" class="nav-link {{ request()->routeIs('homepage.cta') ? 'active' : '' }}"><span class="link-text">Call To Action (CTA)</span></a>
                <a href="{{ route_if_exists('homepage.newsletter') }}" class="nav-link {{ request()->routeIs('homepage.newsletter') ? 'active' : '' }}"><span class="link-text">Newsletter Section</span></a>
                <a href="{{ route_if_exists('homepage.partners') }}" class="nav-link {{ request()->routeIs('homepage.partners') ? 'active' : '' }}"><span class="link-text">Partners / Clients</span></a>
                <a href="{{ route_if_exists('homepage.gallery') }}" class="nav-link {{ request()->routeIs('homepage.gallery') ? 'active' : '' }}"><span class="link-text">Gallery Section</span></a>
                <a href="{{ route_if_exists('homepage.contact') }}" class="nav-link {{ request()->routeIs('homepage.contact') ? 'active' : '' }}"><span class="link-text">Contact Section</span></a>
                <a href="{{ route_if_exists('homepage.social-media') }}" class="nav-link {{ request()->routeIs('homepage.social-media') ? 'active' : '' }}"><span class="link-text">Social Media</span></a>
                <a href="{{ route_if_exists('homepage.footer-widgets') }}" class="nav-link {{ request()->routeIs('homepage.footer-widgets') ? 'active' : '' }}"><span class="link-text">Footer Homepage Widgets</span></a>
                <a href="{{ route_if_exists('homepage.theme') }}" class="nav-link {{ request()->routeIs('homepage.theme') ? 'active' : '' }}"><span class="link-text">Theme & Appearance</span></a>
                <a href="{{ route_if_exists('homepage.seo') }}" class="nav-link {{ request()->routeIs('homepage.seo') ? 'active' : '' }}"><span class="link-text">SEO Settings</span></a>
                <a href="{{ route_if_exists('homepage.section-manager') }}" class="nav-link {{ request()->routeIs('homepage.section-manager') ? 'active' : '' }}"><span class="link-text">Section Manager</span></a>
                <a href="{{ route_if_exists('menu-manage.index') }}" class="nav-link {{ request()->routeIs('menu-manage*') ? 'active' : '' }}"><span class="link-text">Menu Manage</span></a>
                <a href="{{ route_if_exists('custom-pages.index') }}" class="nav-link {{ request()->routeIs('custom-pages*') ? 'active' : '' }}"><span class="link-text">Custom Pages</span></a>
            </div>
        </div>
    </div>
    <div class="nav-item">
        <a href="{{ route_if_exists('activity-logs.index') }}" class="nav-link {{ request()->routeIs('activity-logs*') ? 'active' : '' }}">
            <i class="fas fa-history"></i><span class="link-text">Activity Logs</span>
        </a>
    </div>
    <div class="nav-item">
        <a class="nav-link has-submenu" data-bs-toggle="collapse" href="#userSetupMenu" role="button" aria-expanded="false">
            <i class="fas fa-user-cog"></i><span class="link-text">User Setup</span>
        </a>
        <div class="collapse" id="userSetupMenu">
            <div class="collapse-submenu">
                <a href="{{ route_if_exists('users.index') }}" class="nav-link {{ request()->routeIs('users*') ? 'active' : '' }}"><span class="link-text">Users</span></a>
                <a href="{{ route_if_exists('roles.index') }}" class="nav-link {{ request()->routeIs('roles*') ? 'active' : '' }}"><span class="link-text">Role &amp; Permission</span></a>
            </div>
        </div>
    </div>
</nav>