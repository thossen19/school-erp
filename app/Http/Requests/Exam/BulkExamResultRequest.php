<?php

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class BulkExamResultRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'exam_id' => 'required|integer|exists:exams,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'results' => 'required|array|min:1',
            'results.*.student_id' => 'required|integer|exists:students,id',
            'results.*.marks_obtained' => 'required|numeric|min:0',
            'results.*.max_marks' => 'required|numeric|min:0',
            'results.*.is_absent' => 'boolean',
            'results.*.remarks' => 'nullable|string|max:500',
        ];
    }
}
