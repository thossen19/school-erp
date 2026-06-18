<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'locale' => 'sometimes|string|in:en,fr,es,ar,zh',
            'theme_preference' => 'sometimes|string|in:light,dark,auto',
            'bio' => 'nullable|string|max:1000',
            'address' => 'nullable|string|max:500',
        ];
    }
}
