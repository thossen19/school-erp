<?php

namespace App\Http\Requests\Fee;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeeCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:fee_categories,code',
            'description' => 'nullable|string|max:500',
            'is_recurring' => 'boolean',
            'frequency' => 'nullable|string|in:monthly,quarterly,half_yearly,yearly,one_time',
            'is_active' => 'boolean',
        ];
    }
}
