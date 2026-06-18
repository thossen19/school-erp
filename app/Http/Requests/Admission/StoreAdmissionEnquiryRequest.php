<?php

namespace App\Http\Requests\Admission;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdmissionEnquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'class_id' => 'required|integer|exists:classes,id',
            'source' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
            'follow_up_date' => 'nullable|date',
        ];
    }
}
