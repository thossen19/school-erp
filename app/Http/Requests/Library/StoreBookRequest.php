<?php

namespace App\Http\Requests\Library;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|max:50|unique:books,isbn',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'category_id' => 'required|integer|exists:book_categories,id',
            'language' => 'nullable|string|max:50',
            'edition' => 'nullable|string|max:50',
            'published_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'pages' => 'nullable|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'available_quantity' => 'required|integer|min:0|lte:quantity',
            'rack_no' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:2000',
            'status' => 'nullable|string|in:available,damaged,lost,withdrawn',
        ];
    }
}
