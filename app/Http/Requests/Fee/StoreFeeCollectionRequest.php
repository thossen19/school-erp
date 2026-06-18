<?php

namespace App\Http\Requests\Fee;

use App\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFeeCollectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => 'required|integer|exists:students,id',
            'fee_structure_id' => 'required|integer|exists:fee_structures,id',
            'fee_category_id' => 'required|integer|exists:fee_categories,id',
            'amount' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'fine_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'balance_amount' => 'required|numeric|min:0',
            'payment_method' => ['required', Rule::in(PaymentMethod::all())],
            'payment_date' => 'required|date',
            'transaction_id' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:255',
            'cheque_no' => 'nullable|string|max:50',
            'cheque_date' => 'nullable|date',
            'remarks' => 'nullable|string|max:500',
            'installment_id' => 'nullable|integer|exists:fee_installments,id',
            'academic_year_id' => 'required|integer|exists:academic_years,id',
            'discount_id' => 'nullable|integer|exists:fee_discounts,id',
        ];
    }
}
