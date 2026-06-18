<?php

namespace App\Http\Requests\Hr;

use App\Enums\BloodGroup;
use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Enums\Religion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'employee_no' => 'required|string|max:50|unique:employees,employee_no',
            'email' => 'required|email|max:255|unique:employees,email',
            'personal_email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'emergency_contact' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => ['required', Rule::in(Gender::all())],
            'marital_status' => ['nullable', Rule::in(MaritalStatus::all())],
            'blood_group' => ['nullable', Rule::in(BloodGroup::all())],
            'religion' => ['nullable', Rule::in(Religion::all())],
            'nationality' => 'nullable|string|max:100',
            'department_id' => 'required|integer|exists:departments,id',
            'designation_id' => 'required|integer|exists:designations,id',
            'qualification' => 'nullable|string|max:500',
            'experience_years' => 'nullable|integer|min:0|max:70',
            'joining_date' => 'required|date',
            'contract_end_date' => 'nullable|date|after:joining_date',
            'employment_type' => 'required|string|in:permanent,contract,temporary,probation,intern',
            'work_shift' => 'nullable|string|max:50',
            'present_address' => 'nullable|string|max:500',
            'permanent_address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'status' => 'nullable|string|in:active,inactive,terminated,resigned,suspended',
            'branch_id' => 'nullable|integer|exists:branches,id',
        ];
    }
}
