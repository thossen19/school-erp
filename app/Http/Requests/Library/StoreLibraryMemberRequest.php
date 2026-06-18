<?php

namespace App\Http\Requests\Library;

use Illuminate\Foundation\Http\FormRequest;

class StoreLibraryMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:library_members,code',
            'member_type' => 'required|string|in:student,teacher,staff',
            'memberable_id' => 'required|integer',
            'memberable_type' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'max_books_allowed' => 'nullable|integer|min:1',
            'max_days_allowed' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after_or_equal:valid_from',
            'status' => 'nullable|string|in:active,inactive,suspended',
        ];
    }
}
