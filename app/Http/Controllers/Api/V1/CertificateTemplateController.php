<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Certificate\CertificateTemplate;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CertificateTemplateController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $templates = CertificateTemplate::withCount('certificates')->when($request->type, fn($q) => $q->where('type', $request->type))->when($request->is_active, fn($q) => $q->where('is_active', $request->boolean('is_active')))->orderBy('name')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($templates, 'Certificate templates retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:certificate_templates,code',
            'type' => 'required|string|in:transfer,character,conduct,achievement,participation,experience,bonafide,other',
            'content' => 'required|string|max:10000',
            'orientation' => 'nullable|string|in:portrait,landscape',
            'page_size' => 'nullable|string|in:A4,letter,legal',
            'margin_top' => 'nullable|integer|min:0|max:100',
            'margin_bottom' => 'nullable|integer|min:0|max:100',
            'margin_left' => 'nullable|integer|min:0|max:100',
            'margin_right' => 'nullable|integer|min:0|max:100',
            'font_family' => 'nullable|string|max:100',
            'font_size' => 'nullable|integer|min:8|max:72',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'is_active' => 'boolean',
        ]);

        $template = CertificateTemplate::create($validated);

        if ($request->hasFile('background_image')) {
            $template->update(['background_image' => $request->file('background_image')->store('certificates/templates', 'public')]);
        }

        return $this->createdResponse($template, 'Certificate template created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(CertificateTemplate::findOrFail($id), 'Certificate template retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $template = CertificateTemplate::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:50|unique:certificate_templates,code,' . $id,
            'type' => 'sometimes|string|in:transfer,character,conduct,achievement,participation,experience,bonafide,other',
            'content' => 'sometimes|string|max:10000',
            'orientation' => 'nullable|string|in:portrait,landscape',
            'page_size' => 'nullable|string|in:A4,letter,legal',
            'margin_top' => 'nullable|integer|min:0|max:100',
            'margin_bottom' => 'nullable|integer|min:0|max:100',
            'margin_left' => 'nullable|integer|min:0|max:100',
            'margin_right' => 'nullable|integer|min:0|max:100',
            'font_family' => 'nullable|string|max:100',
            'font_size' => 'nullable|integer|min:8|max:72',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'is_active' => 'boolean',
        ]);

        $template->update($validated);

        if ($request->hasFile('background_image')) {
            $template->update(['background_image' => $request->file('background_image')->store('certificates/templates', 'public')]);
        }

        return $this->updatedResponse($template->fresh(), 'Certificate template updated');
    }

    public function destroy(int $id): JsonResponse
    {
        CertificateTemplate::findOrFail($id)->delete();
        return $this->deletedResponse('Certificate template deleted');
    }
}
