<?php

namespace App\Http\Requests\Hostel;

use Illuminate\Foundation\Http\FormRequest;

class StoreHostelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:hostels,code',
            'type' => 'required|string|in:boys,girls,co_ed',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'warden_name' => 'nullable|string|max:255',
            'warden_phone' => 'nullable|string|max:20',
            'total_rooms' => 'nullable|integer|min:0',
            'total_beds' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|string|in:active,inactive,maintenance',
            'branch_id' => 'nullable|integer|exists:branches,id',
        ];
    }
}
