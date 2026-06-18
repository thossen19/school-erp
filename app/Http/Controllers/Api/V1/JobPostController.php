<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Alumni\JobPost;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobPostController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $posts = JobPost::withCount('applications')->when($request->type, fn($q) => $q->where('employment_type', $request->type))->when($request->category, fn($q) => $q->where('category', $request->category))->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%")->orWhere('company', 'like', "%{$request->search}%"))->orderBy('posted_at', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($posts, 'Job posts retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'employment_type' => 'required|string|in:full_time,part_time,contract,internship,freelance',
            'category' => 'nullable|string|in:teaching,administrative,technical,support,other',
            'description' => 'required|string|max:10000',
            'requirements' => 'nullable|string|max:10000',
            'salary_range' => 'nullable|string|max:100',
            'application_url' => 'nullable|url|max:500',
            'application_email' => 'nullable|email|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'posted_by' => 'nullable|integer|exists:users,id',
            'posted_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:posted_at',
            'status' => 'nullable|string|in:active,closed,draft',
        ]);

        $post = JobPost::create($validated);
        return $this->createdResponse($post, 'Job post created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(JobPost::findOrFail($id), 'Job post retrieved');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $post = JobPost::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:10000',
            'requirements' => 'nullable|string|max:10000',
            'salary_range' => 'nullable|string|max:100',
            'application_url' => 'nullable|url|max:500',
            'expires_at' => 'nullable|date',
            'status' => 'nullable|string|in:active,closed,draft',
        ]);
        $post->update($validated);
        return $this->updatedResponse($post->fresh(), 'Job post updated');
    }

    public function destroy(int $id): JsonResponse
    {
        JobPost::findOrFail($id)->delete();
        return $this->deletedResponse('Job post deleted');
    }
}
