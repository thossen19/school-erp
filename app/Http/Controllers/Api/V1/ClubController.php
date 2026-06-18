<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Events\Club;
use App\Services\Events\ClubService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    use ApiResponseTrait;

    protected ClubService $clubService;

    public function __construct(ClubService $clubService)
    {
        $this->clubService = $clubService;
    }

    public function index(Request $request): JsonResponse
    {
        $clubs = Club::with('coordinator')->withCount('members')->when($request->type, fn($q) => $q->where('type', $request->type))->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))->orderBy('name')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($clubs, 'Clubs retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:clubs,code',
            'type' => 'required|string|in:academic,sports,cultural,arts,music,drama,technology,social_service,other',
            'description' => 'nullable|string|max:2000',
            'meeting_schedule' => 'nullable|string|max:500',
            'meeting_venue' => 'nullable|string|max:255',
            'coordinator_id' => 'nullable|integer|exists:employees,id',
            'max_members' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $club = Club::create($validated);

        if ($request->hasFile('logo')) {
            $club->update(['logo' => $request->file('logo')->store('clubs', 'public')]);
        }

        return $this->createdResponse($club->load('coordinator'), 'Club created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            Club::with('coordinator', 'members.student')->withCount('members')->findOrFail($id),
            'Club retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $club = Club::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:50|unique:clubs,code,' . $id,
            'type' => 'sometimes|string|in:academic,sports,cultural,arts,music,drama,technology,social_service,other',
            'description' => 'nullable|string|max:2000',
            'meeting_schedule' => 'nullable|string|max:500',
            'meeting_venue' => 'nullable|string|max:255',
            'coordinator_id' => 'nullable|integer|exists:employees,id',
            'max_members' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $club->update($validated);
        return $this->updatedResponse($club->fresh()->load('coordinator'), 'Club updated');
    }

    public function destroy(int $id): JsonResponse
    {
        Club::findOrFail($id)->delete();
        return $this->deletedResponse('Club deleted');
    }

    public function manageMembers(Request $request, int $clubId): JsonResponse
    {
        $request->validate([
            'action' => 'required|string|in:add,remove',
            'student_id' => 'required|integer|exists:students,id',
        ]);

        $club = Club::findOrFail($clubId);

        if ($request->action === 'add') {
            $club->members()->firstOrCreate(['student_id' => $request->student_id]);
            return $this->successResponse(null, 'Member added to club');
        }

        $club->members()->where('student_id', $request->student_id)->delete();
        return $this->successResponse(null, 'Member removed from club');
    }
}
