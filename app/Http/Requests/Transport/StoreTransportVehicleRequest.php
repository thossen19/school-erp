<?php

namespace App\Http\Requests\Transport;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransportVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicle_type' => 'required|string|in:bus,van,car,auto,truck,other',
            'registration_no' => 'required|string|max:50|unique:transport_vehicles,registration_no',
            'model' => 'required|string|max:255',
            'make' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1990|max:' . date('Y'),
            'capacity' => 'required|integer|min:1',
            'color' => 'nullable|string|max:50',
            'chassis_no' => 'nullable|string|max:100',
            'engine_no' => 'nullable|string|max:100',
            'insurance_expiry' => 'nullable|date',
            'fitness_expiry' => 'nullable|date',
            'fuel_type' => 'nullable|string|in:petrol,diesel,cng,electric',
            'status' => 'nullable|string|in:active,inactive,maintenance,retired',
        ];
    }
}
