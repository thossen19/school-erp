<?php

namespace App\Http\Requests\Timetable;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomAllocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:room_allocations,code',
            'type' => 'required|string|in:classroom,laboratory,library,office,auditorium,other',
            'capacity' => 'required|integer|min:1',
            'floor' => 'nullable|string|max:50',
            'building' => 'nullable|string|max:255',
            'facilities' => 'nullable|array',
            'is_available' => 'boolean',
            'branch_id' => 'nullable|integer|exists:branches,id',
            'description' => 'nullable|string|max:500',
        ];
    }
}
