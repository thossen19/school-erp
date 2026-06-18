<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Library\StoreBookIssueRequest;
use App\Models\Library\BookIssue;
use App\Services\Library\BookIssueService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookIssueController extends Controller
{
    use ApiResponseTrait;

    protected BookIssueService $bookIssueService;

    public function __construct(BookIssueService $bookIssueService)
    {
        $this->bookIssueService = $bookIssueService;
    }

    public function index(Request $request): JsonResponse
    {
        $issues = BookIssue::with('book:id,title,isbn', 'member:id,name,code')->when($request->book_id, fn($q) => $q->where('book_id', $request->book_id))->when($request->member_id, fn($q) => $q->where('member_id', $request->member_id))->when($request->status, fn($q) => $q->where('status', $request->status))->when($request->date_from, fn($q) => $q->whereDate('issue_date', '>=', $request->date_from))->when($request->date_to, fn($q) => $q->whereDate('issue_date', '<=', $request->date_to))->orderBy('issue_date', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($issues, 'Book issues retrieved');
    }

    public function store(StoreBookIssueRequest $request): JsonResponse
    {
        $issue = $this->bookIssueService->issueBook($request->validated());
        return $this->createdResponse($issue->load('book', 'member'), 'Book issued');
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse(
            BookIssue::with('book', 'member', 'fines')->findOrFail($id),
            'Book issue retrieved'
        );
    }

    public function issue(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'book_id' => 'required|integer|exists:books,id',
            'member_id' => 'required|integer|exists:library_members,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'notes' => 'nullable|string|max:500',
        ]);

        $issue = $this->bookIssueService->issueBook($validated);
        return $this->createdResponse($issue->load('book', 'member'), 'Book issued');
    }

    public function returnBook(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'return_date' => 'required|date',
            'condition' => 'nullable|string|in:good,damaged,lost',
            'notes' => 'nullable|string|max:500',
        ]);

        $issue = BookIssue::findOrFail($id);
        $issue->update([
            'return_date' => $request->return_date,
            'status' => $request->condition === 'lost' ? 'lost' : 'returned',
            'condition' => $request->condition ?? 'good',
            'notes' => $request->notes,
        ]);

        if ($request->condition !== 'lost') {
            $issue->book()->increment('available_quantity');
        }

        $this->bookIssueService->calculateFine($issue);

        return $this->successResponse($issue->load('book', 'member', 'fines'), 'Book returned');
    }

    public function renew(Request $request, int $id): JsonResponse
    {
        $request->validate(['new_due_date' => 'required|date|after:today']);
        $issue = BookIssue::findOrFail($id);
        $issue->update(['due_date' => $request->new_due_date, 'renewal_count' => $issue->renewal_count + 1]);
        return $this->successResponse($issue->load('book', 'member'), 'Book renewed');
    }

    public function calculateFine(int $id): JsonResponse
    {
        $issue = BookIssue::findOrFail($id);
        $fine = $this->bookIssueService->calculateFine($issue);
        return $this->successResponse($fine, 'Fine calculated');
    }
}
