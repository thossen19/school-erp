<?php

namespace App\Http\Requests\Auth;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'username' => 'sometimes|string|max:50|unique:users,username',
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => 'sometimes|string|max:20',
            'user_type' => ['sometimes', 'string', \Illuminate\Validation\Rule::in(UserRole::all())],
            'school_id' => 'sometimes|integer|exists:schools,id',
            'branch_id' => 'sometimes|integer|exists:branches,id',
        ];
    }
}
