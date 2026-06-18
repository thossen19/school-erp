<?php

namespace App\Http\Requests\Certificate;

use Illuminate\Foundation\Http\FormRequest;

class StoreCertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'certificate_type_id' => 'required|integer|exists:certificate_types,id',
            'template_id' => 'required|integer|exists:certificate_templates,id',
            'student_id' => 'nullable|integer|exists:students,id',
            'employee_id' => 'nullable|integer|exists:employees,id',
            'issue_date' => 'required|date',
            'certificate_no' => 'required|string|max:50|unique:certificates,certificate_no',
            'purpose' => 'nullable|string|max:500',
            'remarks' => 'nullable|string|max:1000',
            'custom_fields' => 'nullable|array',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->input('student_id') && !$this->input('employee_id')) {
                $validator->errors()->add('student_id', 'Either student or employee must be specified.');
            }
        });
    }
}
