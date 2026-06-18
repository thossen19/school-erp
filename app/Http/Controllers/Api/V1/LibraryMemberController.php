<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Library\LibraryMember;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LibraryMemberController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $members = LibraryMember::with('memberable')->withCount('bookIssues')->when($request->member_type, fn($q) => $q->where('member_type', $request->member_type))->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")->orWhere('code', 'like', "%{$request->search}%"))->orderBy('name')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($members, 'Library members retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:library_members,code',
            'member_type' => 'required|string|in:student,teacher,staff',
            'memberable_id' => 'required|integer',
            'memberable_type' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'max_books_allowed' => 'nullable|integer|min:1',
            'max_days_allowed' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after_or_equal:valid_from',
            'status' => 'nullable|string|in:active,inactive,suspended',
        ]);

        $member = LibraryMember::create($validated);
        return $this->createdResponse($member, 'Library member created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            LibraryMember::with('memberable', 'bookIssues.book', 'fines')->findOrFail($id),
            'Library member retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $member = LibraryMember::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:50|unique:library_members,code,' . $id,
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'max_books_allowed' => 'nullable|integer|min:1',
            'max_days_allowed' => 'nullable|integer|min:1',
            'valid_to' => 'nullable|date',
            'status' => 'nullable|string|in:active,inactive,suspended',
        ]);
        $member->update($validated);
        return $this->updatedResponse($member->fresh(), 'Library member updated');
    }

    public function destroy(int $id): JsonResponse
    {
        LibraryMember::findOrFail($id)->delete();
        return $this->deletedResponse('Library member deleted');
    }
}
