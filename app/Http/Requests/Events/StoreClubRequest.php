<?php

namespace App\Http\Requests\Events;

use Illuminate\Foundation\Http\FormRequest;

class StoreClubRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:clubs,code',
            'type' => 'required|string|in:academic,sports,cultural,arts,music,drama,technology,social_service,other',
            'description' => 'nullable|string|max:2000',
            'meeting_schedule' => 'nullable|string|max:500',
            'meeting_venue' => 'nullable|string|max:255',
            'coordinator_id' => 'nullable|integer|exists:employees,id',
            'max_members' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ];
    }
}
