<?php

namespace App\Http\Requests\FrontOffice;

use Illuminate\Foundation\Http\FormRequest;

class StoreVisitorRequest extends FormRequest
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
            'address' => 'nullable|string|max:500',
            'purpose' => 'required|string|max:500',
            'person_to_meet' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'id_proof' => 'nullable|string|max:100',
            'id_proof_no' => 'nullable|string|max:100',
            'vehicle_no' => 'nullable|string|max:50',
            'no_of_persons' => 'nullable|integer|min:1|max:50',
            'notes' => 'nullable|string|max:500',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
