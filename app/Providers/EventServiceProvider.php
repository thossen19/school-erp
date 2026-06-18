<?php

namespace App\Providers;

use App\Events\AdmissionApproved;
use App\Events\AttendanceMarked;
use App\Events\EmployeePayrollProcessed;
use App\Events\ExamResultPublished;
use App\Events\FeeCollected;
use App\Events\LeaveApplied;
use App\Events\StudentCreated;
use App\Listeners\CreateStudentAccount;
use App\Listeners\CreateStudentRecord;
use App\Listeners\NotifyApprover;
use App\Listeners\SendApprovalNotification;
use App\Listeners\SendParentNotification;
use App\Listeners\SendPayslip;
use App\Listeners\SendReceipt;
use App\Listeners\SendResultNotification;
use App\Listeners\SendWelcomeNotification;
use App\Listeners\UpdateAnalytics;
use App\Listeners\UpdateDueTracking;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        StudentCreated::class => [
            SendWelcomeNotification::class,
            CreateStudentRecord::class,
        ],

        FeeCollected::class => [
            UpdateDueTracking::class,
            SendReceipt::class,
        ],

        AttendanceMarked::class => [
            SendParentNotification::class,
            UpdateAnalytics::class,
        ],

        LeaveApplied::class => [
            NotifyApprover::class,
        ],

        EmployeePayrollProcessed::class => [
            SendPayslip::class,
        ],

        ExamResultPublished::class => [
            SendResultNotification::class,
        ],

        AdmissionApproved::class => [
            CreateStudentAccount::class,
            SendApprovalNotification::class,
        ],
    ];

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
