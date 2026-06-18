<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\StoreSectionRequest;
use App\Models\Academic\Section;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $sections = Section::with('class')->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))->orderBy('name')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($sections, 'Sections retrieved');
    }

    public function store(StoreSectionRequest $request): JsonResponse
    {
        $section = Section::create($request->validated());
        return $this->createdResponse($section->load('class'), 'Section created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            Section::with('class')->withCount('students')->findOrFail($id),
            'Section retrieved'
        );
    }

    public function update(StoreSectionRequest $request, int $id): JsonResponse
    {
        $section = Section::findOrFail($id);
        $section->update($request->validated());
        return $this->updatedResponse($section->fresh()->load('class'), 'Section updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Section::findOrFail($id)->delete();
        return $this->deletedResponse('Section deleted');
    }
}
