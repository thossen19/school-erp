<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\StoreSubjectRequest;
use App\Models\Academic\Subject;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $subjects = Subject::withCount('classes', 'teachers')->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")->orWhere('code', 'like', "%{$request->search}%"))->when($request->type, fn($q) => $q->where('type', $request->type))->when($request->is_active, fn($q) => $q->where('is_active', $request->boolean('is_active')))->orderBy('name')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($subjects, 'Subjects retrieved');
    }

    public function store(StoreSubjectRequest $request): JsonResponse
    {
        $subject = Subject::create($request->validated());
        return $this->createdResponse($subject, 'Subject created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            Subject::with('classes.class', 'teachers.employee')->findOrFail($id),
            'Subject retrieved'
        );
    }

    public function update(StoreSubjectRequest $request, int $id): JsonResponse
    {
        $subject = Subject::findOrFail($id);
        $subject->update($request->validated());
        return $this->updatedResponse($subject->fresh(), 'Subject updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Subject::findOrFail($id)->delete();
        return $this->deletedResponse('Subject deleted');
    }

    public function assignToClass(Request $request, int $subjectId): JsonResponse
    {
        $request->validate([
            'class_ids' => 'required|array|min:1',
            'class_ids.*' => 'integer|exists:classes,id',
        ]);

        $subject = Subject::findOrFail($subjectId);
        $subject->classes()->syncWithoutDetaching($request->class_ids);

        return $this->successResponse($subject->load('classes.class'), 'Subject assigned to classes');
    }

    public function assignToTeacher(Request $request, int $subjectId): JsonResponse
    {
        $request->validate([
            'employee_id' => 'required|integer|exists:employees,id',
            'class_id' => 'required|integer|exists:classes,id',
        ]);

        $subject = Subject::findOrFail($subjectId);
        $subject->teachers()->syncWithoutDetaching([
            $request->employee_id => ['class_id' => $request->class_id],
        ]);

        return $this->successResponse($subject->load('teachers.employee'), 'Teacher assigned to subject');
    }
}
