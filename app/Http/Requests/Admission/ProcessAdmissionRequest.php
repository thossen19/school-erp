<?php

namespace App\Http\Requests\Admission;

use Illuminate\Foundation\Http\FormRequest;

class ProcessAdmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|string|in:approved,rejected,waiting_list',
            'remarks' => 'nullable|string|max:1000',
            'class_id' => 'required_if:status,approved|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'house_id' => 'nullable|integer|exists:student_houses,id',
            'admission_no' => 'nullable|string|max:50|unique:students,admission_no',
            'admission_date' => 'nullable|date',
            'roll_no' => 'nullable|string|max:20',
            'rejection_reason' => 'required_if:status,rejected|string|max:500',
        ];
    }
}
