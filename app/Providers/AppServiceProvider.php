<?php

namespace App\Providers;

use App\Models\Student\Student;
use App\Models\User;
use App\Observers\StudentObserver;
use App\Services\Academic\AcademicService;
use App\Services\Accounting\AccountingService;
use App\Services\Admission\AdmissionService;
use App\Services\Assessment\ExamService;
use App\Services\Assessment\GradingService;
use App\Services\Asset\AssetService;
use App\Services\Attendance\AttendanceService;
use App\Services\Attendance\HolidayService;
use App\Services\Attendance\LeaveService;
use App\Services\Certificate\CertificateService;
use App\Services\Events\EventService;
use App\Services\Fee\FeeService;
use App\Services\FrontOffice\FrontOfficeService;
use App\Services\Health\HealthService;
use App\Services\Hostel\HostelService;
use App\Services\Hr\HrService;
use App\Services\Inventory\InventoryService;
use App\Services\Library\LibraryService;
use App\Services\Mis\MisService;
use App\Services\Payroll\PayrollService;
use App\Services\Student\StudentService;
use App\Services\Timetable\TimetableService;
use App\Services\Transport\TransportService;
use Barryvdh\DomPDF\ServiceProvider as DomPDFServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(DomPDFServiceProvider::class);

        $this->app->singleton(StudentService::class);
        $this->app->singleton(AttendanceService::class);
        $this->app->singleton(FeeService::class);
        $this->app->singleton(ExamService::class);
        $this->app->singleton(GradingService::class);
        $this->app->singleton(HrService::class);
        $this->app->singleton(AcademicService::class);
        $this->app->singleton(HolidayService::class);
        $this->app->singleton(LeaveService::class);
        $this->app->singleton(CertificateService::class);
        $this->app->singleton(EventService::class);
        $this->app->singleton(AccountingService::class);
        $this->app->singleton(AdmissionService::class);
        $this->app->singleton(AssetService::class);
        $this->app->singleton(HealthService::class);
        $this->app->singleton(HostelService::class);
        $this->app->singleton(InventoryService::class);
        $this->app->singleton(LibraryService::class);
        $this->app->singleton(MisService::class);
        $this->app->singleton(PayrollService::class);
        $this->app->singleton(TimetableService::class);
        $this->app->singleton(TransportService::class);
        $this->app->singleton(FrontOfficeService::class);
    }

    public function boot(): void
    {
        Schema::defaultStringLength(125);
        Model::shouldBeStrict(!$this->app->isProduction());

        Student::observe(StudentObserver::class);

        RateLimiter::for('api', function (mixed $request): Limit {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        Blade::if('role', function (string $role): bool {
            return auth()->check() && auth()->user()->hasRole($role);
        });

        Blade::if('permission', function (string $permission): bool {
            return auth()->check() && auth()->user()->can($permission);
        });

        Blade::if('school', function (mixed $schoolId = null): bool {
            if (!auth()->check()) {
                return false;
            }
            $userSchoolId = auth()->user()->school_id;
            if ($schoolId === null) {
                return $userSchoolId !== null;
            }
            return (int) $userSchoolId === (int) $schoolId;
        });

        Blade::directive('money', function (string $expression): string {
            return "<?php echo '₹' . number_format({$expression}, 2); ?>";
        });

        Str::macro('initials', function (string $name): string {
            $words = explode(' ', $name);
            $initials = '';
            foreach ($words as $word) {
                if (!empty($word)) {
                    $initials .= strtoupper($word[0]);
                }
            }
            return strlen($initials) > 2 ? substr($initials, 0, 2) : $initials;
        });
    }
}
