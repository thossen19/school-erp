<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class StoreBudgetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_id' => 'required|integer|exists:chart_of_accounts,id',
            'fiscal_year' => 'required|string|max:20',
            'allocated_amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'status' => 'nullable|string|in:draft,approved,active,closed',
        ];
    }
}
