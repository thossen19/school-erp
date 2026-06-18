<?php

namespace App\Http\Requests\Asset;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'asset_no' => 'required|string|max:50|unique:assets,asset_no',
            'category_id' => 'required|integer|exists:asset_categories,id',
            'purchase_date' => 'nullable|date',
            'purchase_cost' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'warranty_expiry' => 'nullable|date|after:purchase_date',
            'useful_life_years' => 'nullable|integer|min:0|max:100',
            'salvage_value' => 'nullable|numeric|min:0',
            'depreciation_method' => 'nullable|string|in:straight_line,declining_balance,sum_of_years,units_of_production',
            'status' => 'nullable|string|in:available,allocated,under_maintenance,disposed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
