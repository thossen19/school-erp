<?php

namespace App\Http\Requests\Student;

use App\Enums\BloodGroup;
use App\Enums\CasteCategory;
use App\Enums\Gender;
use App\Enums\Religion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
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
            'date_of_birth' => 'required|date|before:today',
            'gender' => ['required', Rule::in(Gender::all())],
            'blood_group' => ['nullable', Rule::in(BloodGroup::all())],
            'religion' => ['nullable', Rule::in(Religion::all())],
            'caste_category' => ['nullable', Rule::in(CasteCategory::all())],
            'caste' => 'nullable|string|max:100',
            'nationality' => 'nullable|string|max:100',
            'mother_tongue' => 'nullable|string|max:100',
            'aadhaar_no' => 'nullable|string|max:20|unique:students,aadhaar_no',
            'email' => 'nullable|email|max:255|unique:students,email',
            'phone' => 'nullable|string|max:20',
            'emergency_contact' => 'nullable|string|max:20',
            'present_address' => 'nullable|string|max:500',
            'permanent_address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'house_id' => 'nullable|integer|exists:student_houses,id',
            'academic_year_id' => 'required|integer|exists:academic_years,id',
            'admission_no' => 'nullable|string|max:50|unique:students,admission_no',
            'admission_date' => 'nullable|date',
            'branch_id' => 'nullable|integer|exists:branches,id',
            'parents' => 'nullable|array',
            'parents.*' => 'integer|exists:parents,id',
            'status' => 'nullable|string|in:active,inactive,transferred,alumni,graduated',
        ];
    }
}
