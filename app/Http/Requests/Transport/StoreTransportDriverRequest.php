<?php

namespace App\Http\Requests\Transport;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransportDriverRequest extends FormRequest
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
            'alternate_phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'license_no' => 'required|string|max:50|unique:transport_drivers,license_no',
            'license_expiry' => 'nullable|date|after:today',
            'license_type' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date|before:today',
            'emergency_contact' => 'nullable|string|max:20',
            'joining_date' => 'nullable|date',
            'status' => 'nullable|string|in:active,inactive,suspended',
        ];
    }
}
