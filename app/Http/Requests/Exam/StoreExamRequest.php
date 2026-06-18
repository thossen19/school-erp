<?php

namespace App\Http\Requests\Exam;

use App\Enums\ExamType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'exam_type' => ['required', Rule::in(ExamType::all())],
            'class_id' => 'required|integer|exists:classes,id',
            'academic_year_id' => 'required|integer|exists:academic_years,id',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string|max:1000',
            'max_marks' => 'required|integer|min:0',
            'passing_marks' => 'required|integer|min:0|lte:max_marks',
            'weightage' => 'nullable|numeric|min:0|max:100',
            'is_published' => 'boolean',
            'subjects' => 'nullable|array',
            'subjects.*.subject_id' => 'integer|exists:subjects,id',
            'subjects.*.max_marks' => 'integer|min:0',
            'subjects.*.passing_marks' => 'integer|min:0',
            'subjects.*.date' => 'nullable|date',
            'subjects.*.start_time' => 'nullable|date_format:H:i',
            'subjects.*.end_time' => 'nullable|date_format:H:i|after:subjects.*.start_time',
        ];
    }
}
