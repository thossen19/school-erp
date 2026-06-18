<?php

namespace App\Http\Requests\Hostel;

use Illuminate\Foundation\Http\FormRequest;

class StoreHostelAllocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => 'required|integer|exists:students,id',
            'hostel_room_id' => 'required|integer|exists:hostel_rooms,id',
            'hostel_bed_id' => 'nullable|integer|exists:hostel_beds,id',
            'academic_year_id' => 'required|integer|exists:academic_years,id',
            'allocation_date' => 'required|date',
            'expected_checkout_date' => 'nullable|date|after:allocation_date',
            'monthly_rent' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|in:active,checked_out,transferred',
        ];
    }
}
