<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'numeric_value' => 'nullable|integer|min:-2|max:12',
            'section_count' => 'nullable|integer|min:0|max:20',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ];
    }
}
