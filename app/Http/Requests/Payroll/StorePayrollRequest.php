<?php

namespace App\Http\Requests\Payroll;

use Illuminate\Foundation\Http\FormRequest;

class StorePayrollRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|integer|exists:employees,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2099',
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|array',
            'allowances.*.name' => 'string|max:255',
            'allowances.*.amount' => 'numeric|min:0',
            'deductions' => 'nullable|array',
            'deductions.*.name' => 'string|max:255',
            'deductions.*.amount' => 'numeric|min:0',
            'gross_salary' => 'required|numeric|min:0',
            'total_deductions' => 'required|numeric|min:0',
            'net_salary' => 'required|numeric|min:0',
            'payment_date' => 'nullable|date',
            'payment_method' => 'nullable|string|in:bank_transfer,cash,cheque',
            'remarks' => 'nullable|string|max:500',
        ];
    }
}
