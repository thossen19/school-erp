<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class StoreChartOfAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:chart_of_accounts,code',
            'type' => 'required|string|in:asset,liability,equity,income,expense',
            'category' => 'nullable|string|max:100',
            'parent_id' => 'nullable|integer|exists:chart_of_accounts,id',
            'description' => 'nullable|string|max:500',
            'opening_balance' => 'nullable|numeric',
            'is_active' => 'boolean',
        ];
    }
}
