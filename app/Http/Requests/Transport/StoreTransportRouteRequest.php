<?php

namespace App\Http\Requests\Transport;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransportRouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'route_name' => 'required|string|max:255',
            'route_no' => 'required|string|max:50|unique:transport_routes,route_no',
            'start_point' => 'required|string|max:255',
            'end_point' => 'required|string|max:255',
            'distance_km' => 'nullable|numeric|min:0',
            'estimated_duration' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:active,inactive',
            'description' => 'nullable|string|max:500',
            'stops' => 'nullable|array',
            'stops.*.name' => 'string|max:255',
            'stops.*.address' => 'nullable|string|max:500',
            'stops.*.latitude' => 'nullable|numeric',
            'stops.*.longitude' => 'nullable|numeric',
            'stops.*.stop_order' => 'integer|min:0',
            'stops.*.pickup_time' => 'nullable|date_format:H:i',
            'stops.*.drop_time' => 'nullable|date_format:H:i',
            'stops.*.fee_amount' => 'nullable|numeric|min:0',
        ];
    }
}
