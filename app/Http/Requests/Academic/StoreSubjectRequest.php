<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code',
            'type' => 'nullable|string|in:core,elective,language,co-curricular,vocational',
            'credit_hours' => 'nullable|integer|min:0|max:20',
            'max_marks' => 'nullable|integer|min:0',
            'passing_marks' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ];
    }
}
