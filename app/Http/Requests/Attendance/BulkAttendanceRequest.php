<?php

namespace App\Http\Requests\Attendance;

use App\Enums\AttendanceType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'date' => 'required|date|before_or_equal:today',
            'records' => 'required|array|min:1',
            'records.*.student_id' => 'required|integer|exists:students,id',
            'records.*.status' => ['required', Rule::in(AttendanceType::all())],
            'records.*.remarks' => 'nullable|string|max:500',
            'records.*.late_minutes' => 'nullable|integer|min:0|max:480',
        ];
    }
}
