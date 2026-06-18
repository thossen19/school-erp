<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Library\Book;
use App\Models\Library\BookIssue;
use App\Models\Library\LibraryMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LibraryController extends Controller
{
    public function members(Request $request)
    {
        $members = DB::table('library_members')
            ->leftJoin('students', 'library_members.student_id', '=', 'students.id')
            ->select('library_members.*', 'students.first_name', 'students.last_name', 'students.admission_no')
            ->orderBy('library_members.created_at', 'desc')
            ->paginate(15);

        return view('library.members', compact('members'));
    }

    public function barcode(Request $request)
    {
        $search = $request->search;
        $books = Book::when($search, function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            })
            ->orderBy('title')
            ->paginate(20);

        return view('library.barcode', compact('books'));
    }

    public function returnsList(Request $request)
    {
        $issues = BookIssue::with('book:id,title', 'libraryMember.student:id,first_name,last_name')
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('book', function ($b) use ($request) {
                    $b->where('title', 'like', "%{$request->search}%");
                });
            })
            ->where('status', '!=', 'returned')
            ->orderBy('due_date', 'asc')
            ->paginate(15);

        return view('library.return', compact('issues'));
    }

    public function fines(Request $request)
    {
        $fines = DB::table('book_issues')
            ->join('books', 'book_issues.book_id', '=', 'books.id')
            ->leftJoin('library_members', 'book_issues.member_id', '=', 'library_members.id')
            ->leftJoin('students', 'library_members.student_id', '=', 'students.id')
            ->select('book_issues.*', 'books.title as book_title', 'students.first_name', 'students.last_name')
            ->where('book_issues.fine_amount', '>', 0)
            ->when($request->status, function ($q) use ($request) {
                if ($request->status === 'paid') {
                    $q->where('book_issues.fine_paid', 1);
                } elseif ($request->status === 'unpaid') {
                    $q->where('book_issues.fine_paid', 0);
                }
            })
            ->orderBy('book_issues.created_at', 'desc')
            ->paginate(15);

        return view('library.fines', compact('fines'));
    }

    public function ebook()
    {
        return view('library.ebook');
    }

    public function reports()
    {
        $totalBooks = Book::count();
        $totalMembers = LibraryMember::count();
        $issuedCount = BookIssue::where('status', 'issued')->count();
        $overdueCount = BookIssue::where('status', 'overdue')->count();
        $lostCount = BookIssue::where('status', 'lost')->count();
        $totalFines = DB::table('book_issues')->where('fine_amount', '>', 0)->sum('fine_amount');
        $collectedFines = DB::table('book_issues')->where('fine_paid', 1)->sum('fine_amount');
        $totalIssues = BookIssue::count();
        $availableBooks = Book::sum('available_quantity');

        return view('library.reports', compact(
            'totalBooks', 'totalMembers', 'issuedCount', 'overdueCount',
            'lostCount', 'totalFines', 'collectedFines', 'totalIssues', 'availableBooks'
        ));
    }
}
