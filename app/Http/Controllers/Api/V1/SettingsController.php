<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    use ApiResponseTrait;

    public function getSchoolSettings(Request $request): JsonResponse
    {
        $schoolId = $request->get('school_id') ?? $request->user()->school_id;
        $school = School::findOrFail($schoolId);
        return $this->successResponse($school->settings ?? [], 'School settings retrieved');
    }

    public function updateSchoolSettings(Request $request): JsonResponse
    {
        $schoolId = $request->get('school_id') ?? $request->user()->school_id;
        $school = School::findOrFail($schoolId);

        $validated = $request->validate([
            'school_name' => 'sometimes|string|max:255',
            'school_email' => 'sometimes|email|max:255',
            'school_phone' => 'sometimes|string|max:20',
            'school_address' => 'sometimes|string|max:500',
            'school_city' => 'sometimes|string|max:100',
            'school_state' => 'sometimes|string|max:100',
            'school_country' => 'sometimes|string|max:100',
            'school_postal_code' => 'sometimes|string|max:20',
            'timezone' => 'nullable|string|max:100',
            'date_format' => 'nullable|string|max:20',
            'time_format' => 'nullable|string|max:20',
            'week_start_day' => 'nullable|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'currency' => 'nullable|string|max:3',
            'academic_year_start_month' => 'nullable|integer|min:1|max:12',
            'attendance_capture_time' => 'nullable|string|max:20',
            'late_threshold_minutes' => 'nullable|integer|min:0',
            'auto_fee_calculation' => 'boolean',
            'enable_parent_portal' => 'boolean',
            'enable_student_portal' => 'boolean',
            'sms_provider' => 'nullable|string|max:100',
            'email_provider' => 'nullable|string|max:100',
        ]);

        $settings = array_merge($school->settings ?? [], $validated);
        $school->update(['settings' => $settings]);

        return $this->updatedResponse($settings, 'School settings updated');
    }

    public function getTheme(Request $request): JsonResponse
    {
        $theme = $request->user()->theme_preference ?? 'light';
        return $this->successResponse(['theme' => $theme], 'Theme retrieved');
    }

    public function updateTheme(Request $request): JsonResponse
    {
        $validated = $request->validate(['theme' => 'required|string|in:light,dark,auto']);
        $request->user()->update(['theme_preference' => $validated['theme']]);
        return $this->successResponse(['theme' => $validated['theme']], 'Theme updated');
    }

    public function getLanguage(Request $request): JsonResponse
    {
        return $this->successResponse(['locale' => $request->user()->locale ?? 'en'], 'Language retrieved');
    }

    public function updateLanguage(Request $request): JsonResponse
    {
        $validated = $request->validate(['locale' => 'required|string|in:en,fr,es,ar,zh']);
        $request->user()->update(['locale' => $validated['locale']]);
        app()->setLocale($validated['locale']);
        return $this->successResponse(['locale' => $validated['locale']], 'Language updated');
    }
}
