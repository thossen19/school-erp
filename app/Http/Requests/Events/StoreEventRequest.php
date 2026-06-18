<?php

namespace App\Http\Requests\Events;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'event_type' => 'required|string|in:academic,sports,cultural,holiday,meeting,exam,other',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after_or_equal:start_time',
            'venue' => 'nullable|string|max:255',
            'is_public' => 'boolean',
            'requires_registration' => 'boolean',
            'max_participants' => 'nullable|integer|min:0',
            'registration_fee' => 'nullable|numeric|min:0',
            'organizer' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'color' => 'nullable|string|max:7',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'target_audience' => 'nullable|array',
            'target_audience.*' => 'string|in:all,students,teachers,parents,staff',
        ];
    }
}
