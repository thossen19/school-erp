<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Library\LibraryFine;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LibraryFineController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request): JsonResponse
    {
        $fines = LibraryFine::with('bookIssue.book', 'bookIssue.member')->when($request->member_id, fn($q) => $q->whereHas('bookIssue', fn($q) => $q->where('member_id', $request->member_id)))->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->date_from, fn($q) => $q->whereDate('created_at', '>=', $request->date_from))->when($request->date_to, fn($q) => $q->whereDate('created_at', '<=', $request->date_to))->orderBy('created_at', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($fines, 'Library fines retrieved');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'book_issue_id' => 'required|integer|exists:book_issues,id',
            'amount' => 'required|numeric|min:0',
            'reason' => 'required|string|max:500',
            'status' => 'nullable|string|in:pending,paid,waived',
        ]);

        $fine = LibraryFine::create($validated);
        return $this->createdResponse($fine->load('bookIssue.book', 'bookIssue.member'), 'Library fine created');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            LibraryFine::with('bookIssue.book', 'bookIssue.member')->findOrFail($id),
            'Library fine retrieved'
        );
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $fine = LibraryFine::findOrFail($id);
        $validated = $request->validate([
            'amount' => 'sometimes|numeric|min:0',
            'status' => 'nullable|string|in:pending,paid,waived',
            'paid_at' => 'nullable|date',
            'payment_method' => 'nullable|string|max:50',
        ]);
        $fine->update($validated);
        return $this->updatedResponse($fine->fresh()->load('bookIssue.book'), 'Library fine updated');
    }

    public function destroy(int $id): JsonResponse
    {
        LibraryFine::findOrFail($id)->delete();
        return $this->deletedResponse('Library fine deleted');
    }
}
