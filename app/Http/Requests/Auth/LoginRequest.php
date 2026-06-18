<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required_without:username|email|exists:users,email',
            'username' => 'required_without:email|string|exists:users,username',
            'password' => 'required|string|min:8',
            'remember' => 'boolean',
            'school_id' => 'sometimes|integer|exists:schools,id',
        ];
    }

    public function messages(): array
    {
        return [
            'email.exists' => 'No account found with this email.',
            'username.exists' => 'No account found with this username.',
        ];
    }
}
