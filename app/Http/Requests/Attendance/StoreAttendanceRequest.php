<?php

namespace App\Http\Requests\Attendance;

use App\Enums\AttendanceType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => 'required|integer|exists:students,id',
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'date' => 'required|date|before_or_equal:today',
            'status' => ['required', Rule::in(AttendanceType::all())],
            'remarks' => 'nullable|string|max:500',
            'late_minutes' => 'nullable|integer|min:0|max:480',
        ];
    }
}
