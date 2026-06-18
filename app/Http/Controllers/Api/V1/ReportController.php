<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Mis\MisService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    use ApiResponseTrait;

    protected MisService $misService;

    public function __construct(MisService $misService)
    {
        $this->misService = $misService;
    }

    public function index(Request $request): JsonResponse
    {
        $reports = $this->misService->getAvailableReports($request->get('school_id'));
        return $this->successResponse($reports, 'Available reports');
    }

    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|string|in:attendance,fee,payroll,admission,exam,student,employee,transport',
            'format' => 'nullable|string|in:pdf,excel,csv',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'filters' => 'nullable|array',
        ]);

        $report = $this->misService->generateReport(
            $request->type,
            $request->format ?? 'pdf',
            $request->only('date_from', 'date_to', 'filters')
        );

        return $this->successResponse($report, 'Report generated');
    }

    public function schedule(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:attendance,fee,payroll,admission,exam,student,employee,transport',
            'frequency' => 'required|string|in:daily,weekly,monthly,quarterly,yearly',
            'format' => 'nullable|string|in:pdf,excel,csv',
            'recipients' => 'required|array|min:1',
            'recipients.*' => 'email',
            'filters' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $scheduled = $this->misService->scheduleReport($request->validated());
        return $this->createdResponse($scheduled, 'Report scheduled');
    }

    public function getKpis(Request $request): JsonResponse
    {
        $kpis = $this->misService->getKpis($request->get('school_id'), $request->get('academic_year_id'));
        return $this->successResponse($kpis, 'KPIs retrieved');
    }

    public function getAnalytics(Request $request): JsonResponse
    {
        $analytics = $this->misService->getOverallAnalytics($request->get('school_id'));
        return $this->successResponse($analytics, 'Analytics data');
    }

    public function getDashboardData(Request $request): JsonResponse
    {
        $data = $this->misService->getDashboardData(
            $request->get('school_id'),
            $request->get('academic_year_id'),
            $request->user()
        );
        return $this->successResponse($data, 'Dashboard data');
    }
}
