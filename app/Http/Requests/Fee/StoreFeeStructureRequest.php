<?php

namespace App\Http\Requests\Fee;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeeStructureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fee_category_id' => 'required|integer|exists:fee_categories,id',
            'class_id' => 'required|integer|exists:classes,id',
            'academic_year_id' => 'required|integer|exists:academic_years,id',
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'is_optional' => 'boolean',
            'is_active' => 'boolean',
            'description' => 'nullable|string|max:500',
            'installments' => 'nullable|array',
            'installments.*.name' => 'required|string|max:255',
            'installments.*.due_date' => 'required|date',
            'installments.*.amount' => 'required|numeric|min:0',
            'installments.*.late_fee' => 'nullable|numeric|min:0',
        ];
    }
}
