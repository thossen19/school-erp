<?php

namespace App\Http\Requests\Hostel;

use Illuminate\Foundation\Http\FormRequest;

class StoreHostelRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hostel_id' => 'required|integer|exists:hostels,id',
            'room_no' => 'required|string|max:50|unique:hostel_rooms,room_no',
            'floor' => 'nullable|string|max:50',
            'room_type' => 'required|string|in:single,double,triple,dormitory',
            'capacity' => 'required|integer|min:1',
            'rent_amount' => 'nullable|numeric|min:0',
            'facilities' => 'nullable|array',
            'status' => 'nullable|string|in:available,occupied,maintenance,reserved',
            'notes' => 'nullable|string|max:500',
        ];
    }
}
