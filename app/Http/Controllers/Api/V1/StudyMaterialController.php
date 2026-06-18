<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Academic\StudyMaterial;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudyMaterialController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $materials = StudyMaterial::with('subject', 'class', 'uploader')->when($request->subject_id, fn($q) => $q->where('subject_id', $request->subject_id))->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))->when($request->type, fn($q) => $q->where('type', $request->type))->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($materials, 'Study materials retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'subject_id' => 'required|integer|exists:subjects,id',
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'nullable|integer|exists:sections,id',
            'type' => 'required|string|in:notes,worksheet,reference_book,video,audio,presentation,other',
            'file' => 'nullable|file|max:51200',
            'url' => 'nullable|url|max:500',
            'tags' => 'nullable|array',
            'is_public' => 'boolean',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('study-materials', 'public');
        }

        $material = StudyMaterial::create(array_merge($validated, ['uploaded_by' => $request->user()->id]));
        return $this->createdResponse($material->load('subject', 'class', 'uploader'), 'Study material uploaded');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(StudyMaterial::with('subject', 'class', 'uploader')->findOrFail($id), 'Study material retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $material = StudyMaterial::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:2000',
            'type' => 'sometimes|string|in:notes,worksheet,reference_book,video,audio,presentation,other',
            'file' => 'nullable|file|max:51200',
            'url' => 'nullable|url|max:500',
            'tags' => 'nullable|array',
            'is_public' => 'boolean',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('study-materials', 'public');
        }

        $material->update($validated);
        return $this->updatedResponse($material->fresh()->load('subject', 'class'), 'Study material updated');
    }

    public function destroy(int $id): JsonResponse
    {
        StudyMaterial::findOrFail($id)->delete();
        return $this->deletedResponse('Study material deleted');
    }
}
