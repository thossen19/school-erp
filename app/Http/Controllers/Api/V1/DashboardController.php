<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Mis\MisService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    use ApiResponseTrait;

    protected MisService $misService;

    public function __construct(MisService $misService)
    {
        $this->misService = $misService;
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $userType = $user->user_type;
        $schoolId = $request->get('school_id');

        $stats = match ($userType) {
            'super_admin' => $this->misService->getSuperAdminDashboard($schoolId),
            'school_admin', 'principal' => $this->misService->getSchoolAdminDashboard($schoolId),
            'teacher' => $this->misService->getTeacherDashboard($user->id, $schoolId),
            'accountant' => $this->misService->getAccountantDashboard($schoolId),
            'parent' => $this->misService->getParentDashboard($user->id, $schoolId),
            'student' => $this->misService->getStudentDashboard($user->id, $schoolId),
            default => $this->misService->getDefaultDashboard($schoolId),
        };

        return $this->successResponse($stats, 'Dashboard data retrieved');
    }

    public function studentStats(Request $request): JsonResponse
    {
        Gate::allowIf(fn($user) => in_array($user->user_type, ['super_admin', 'school_admin', 'principal', 'teacher']));
        $stats = $this->misService->getStudentStatistics($request->get('school_id'), $request->get('academic_year_id'));
        return $this->successResponse($stats, 'Student statistics retrieved');
    }

    public function admissionStats(Request $request): JsonResponse
    {
        $stats = $this->misService->getAdmissionStatistics($request->get('school_id'), $request->get('academic_year_id'));
        return $this->successResponse($stats, 'Admission statistics retrieved');
    }

    public function attendanceAnalytics(Request $request): JsonResponse
    {
        Gate::allowIf(fn($user) => in_array($user->user_type, ['super_admin', 'school_admin', 'principal', 'teacher']));
        $analytics = $this->misService->getAttendanceAnalytics(
            $request->get('school_id'),
            $request->get('academic_year_id'),
            $request->get('class_id'),
            $request->get('start_date'),
            $request->get('end_date')
        );
        return $this->successResponse($analytics, 'Attendance analytics retrieved');
    }

    public function feeReport(Request $request): JsonResponse
    {
        Gate::allowIf(fn($user) => in_array($user->user_type, ['super_admin', 'school_admin', 'accountant']));
        $report = $this->misService->getFeeReport($request->get('school_id'), $request->get('academic_year_id'));
        return $this->successResponse($report, 'Fee report retrieved');
    }

    public function payrollSummary(Request $request): JsonResponse
    {
        Gate::allowIf(fn($user) => in_array($user->user_type, ['super_admin', 'school_admin', 'hr_manager', 'accountant']));
        $summary = $this->misService->getPayrollSummary($request->get('school_id'), $request->get('month'), $request->get('year'));
        return $this->successResponse($summary, 'Payroll summary retrieved');
    }

    public function academicPerformance(Request $request): JsonResponse
    {
        Gate::allowIf(fn($user) => in_array($user->user_type, ['super_admin', 'school_admin', 'principal', 'teacher']));
        $performance = $this->misService->getAcademicPerformance(
            $request->get('school_id'),
            $request->get('academic_year_id'),
            $request->get('class_id'),
            $request->get('exam_id')
        );
        return $this->successResponse($performance, 'Academic performance retrieved');
    }
}
