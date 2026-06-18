<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Library\Book;
use App\Models\Library\BookIssue;
use App\Models\Library\LibraryMember;
use Illuminate\Http\Request;

class BookIssueController extends Controller
{
    public function index(Request $request)
    {
        $issues = BookIssue::with('book:id,title', 'libraryMember.student:id,first_name,last_name', 'issuedBy:id,name')
            ->when($request->status, function ($q) use ($request) {
                if ($request->status === 'returned') {
                    $q->where('status', 'returned');
                } elseif ($request->status === 'overdue') {
                    $q->where('status', 'overdue');
                } else {
                    $q->where('status', $request->status);
                }
            })
            ->when($request->student_id, function ($q) use ($request) {
                $member = LibraryMember::where('student_id', $request->student_id)->first();
                if ($member) {
                    $q->where('member_id', $member->id);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('book-issues.index', compact('issues'));
    }

    public function create()
    {
        $members = LibraryMember::with('student:id,first_name,last_name')->where('is_active', true)->get();
        $books = Book::where('available_quantity', '>', 0)->orderBy('title')->get();
        return view('book-issues.create', compact('members', 'books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|integer|exists:library_members,id',
            'book_id' => 'required|integer|exists:books,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'remarks' => 'nullable|string|max:500',
        ]);

        $validated['school_id'] = $request->user()->school_id ?? 1;
        $validated['issued_by'] = $request->user()->id;
        $validated['status'] = 'issued';

        BookIssue::create($validated);
        Book::where('id', $validated['book_id'])->decrement('available_quantity');

        return redirect()->route('book-issues.index')->with('success', 'Book issued successfully');
    }

    public function show(int $id)
    {
        $issue = BookIssue::with('book', 'libraryMember.student', 'issuedBy')->findOrFail($id);
        return view('book-issues.show', compact('issue'));
    }

    public function returnBook(Request $request, int $id)
    {
        $issue = BookIssue::findOrFail($id);
        $request->validate(['remarks' => 'nullable|string|max:500']);

        $issue->update([
            'return_date' => now(),
            'status' => 'returned',
            'remarks' => $request->remarks,
        ]);
        Book::where('id', $issue->book_id)->increment('available_quantity');

        return redirect()->route('book-issues.index')->with('success', 'Book returned successfully');
    }

    public function renew(Request $request, int $id)
    {
        $issue = BookIssue::findOrFail($id);
        $request->validate(['new_due_date' => 'required|date|after:today']);

        $issue->update(['due_date' => $request->new_due_date]);
        return redirect()->route('book-issues.index')->with('success', 'Book renewed successfully');
    }
}
