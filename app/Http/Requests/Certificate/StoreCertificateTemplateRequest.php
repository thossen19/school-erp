<?php

namespace App\Http\Requests\Certificate;

use Illuminate\Foundation\Http\FormRequest;

class StoreCertificateTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:certificate_templates,code',
            'type' => 'required|string|in:transfer,character,conduct,achievement,participation,experience,bonafide,other',
            'content' => 'required|string|max:10000',
            'orientation' => 'nullable|string|in:portrait,landscape',
            'page_size' => 'nullable|string|in:A4,letter,legal',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'is_active' => 'boolean',
        ];
    }
}
