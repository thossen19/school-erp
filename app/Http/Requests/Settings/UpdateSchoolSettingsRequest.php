<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSchoolSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'school_name' => 'sometimes|string|max:255',
            'school_email' => 'sometimes|email|max:255',
            'school_phone' => 'sometimes|string|max:20',
            'school_address' => 'sometimes|string|max:500',
            'timezone' => 'nullable|string|max:100',
            'date_format' => 'nullable|string|max:20',
            'time_format' => 'nullable|string|max:20',
            'week_start_day' => 'nullable|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'currency' => 'nullable|string|max:3',
            'academic_year_start_month' => 'nullable|integer|min:1|max:12',
            'late_threshold_minutes' => 'nullable|integer|min:0',
            'auto_fee_calculation' => 'boolean',
            'enable_parent_portal' => 'boolean',
            'enable_student_portal' => 'boolean',
        ];
    }
}
