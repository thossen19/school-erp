<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Student\Guardian;
use App\Services\Student\GuardianService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    use ApiResponseTrait;

    protected GuardianService $parentService;

    public function __construct(GuardianService $parentService)
    {
        $this->parentService = $parentService;
    }

    public function index(Request $request): JsonResponse
    {
        $parents = Guardian::withCount('children')->when($request->search, fn($q) => $q->where(function($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")->orWhere('last_name', 'like', "%{$request->search}%")->orWhere('email', 'like', "%{$request->search}%")->orWhere('phone', 'like', "%{$request->search}%");
            }))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($parents, 'Parents retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'father_name' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',
            'father_email' => 'nullable|email|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_income' => 'nullable|numeric|min:0',
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',
            'mother_email' => 'nullable|email|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_income' => 'nullable|numeric|min:0',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_relation' => 'nullable|string|max:100',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
        ]);

        $parent = Guardian::create($validated);
        return $this->createdResponse($parent, 'Parent created');
    }

    public function show(int $id): JsonResponse
    {
        $parent = Guardian::with('children', 'children.class', 'children.section')->findOrFail($id);
        return $this->successResponse($parent, 'Parent retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $parent = Guardian::findOrFail($id);
        $validated = $request->validate([
            'father_name' => 'nullable|string|max:255',
            'father_phone' => 'nullable|string|max:20',
            'father_email' => 'nullable|email|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_income' => 'nullable|numeric|min:0',
            'mother_name' => 'nullable|string|max:255',
            'mother_phone' => 'nullable|string|max:20',
            'mother_email' => 'nullable|email|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_income' => 'nullable|numeric|min:0',
            'guardian_name' => 'nullable|string|max:255',
            'guardian_relation' => 'nullable|string|max:100',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
        ]);
        $parent->update($validated);
        return $this->updatedResponse($parent->fresh(), 'Parent updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Guardian::findOrFail($id)->delete();
        return $this->deletedResponse('Parent deleted');
    }

    public function linkToStudent(Request $request, int $parentId): JsonResponse
    {
        $request->validate([
            'student_id' => 'required|integer|exists:students,id',
            'relationship' => 'nullable|string|max:50',
        ]);

        $parent = Guardian::findOrFail($parentId);
        $parent->children()->syncWithoutDetaching([$request->student_id => ['relationship' => $request->relationship ?? 'father']]);

        return $this->successResponse($parent->load('children'), 'Student linked to parent');
    }

    public function getChildren(int $id): JsonResponse
    {
        $parent = Guardian::with('children', 'children.class', 'children.section', 'children.house')->findOrFail($id);
        return $this->successResponse($parent->children, 'Children retrieved');
    }
}
