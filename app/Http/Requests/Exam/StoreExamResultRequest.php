<?php

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamResultRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'exam_id' => 'required|integer|exists:exams,id',
            'student_id' => 'required|integer|exists:students,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'marks_obtained' => 'required|numeric|min:0',
            'max_marks' => 'required|numeric|min:0',
            'is_absent' => 'boolean',
            'remarks' => 'nullable|string|max:500',
            'grade' => 'nullable|string|max:10',
            'percentage' => 'nullable|numeric|min:0|max:100',
        ];
    }
}
