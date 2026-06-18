<?php

namespace App\Services\Library;

use App\Contracts\RepositoryInterface;
use App\Exceptions\ServiceException;
use App\Models\Library\Book;
use App\Repositories\Library\BookRepository;
use App\Repositories\Library\BookIssueRepository;
use App\Repositories\Library\LibraryMemberRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class LibraryService extends BaseService
{
    protected BookRepository $bookRepository;
    protected BookIssueRepository $bookIssueRepository;
    protected LibraryMemberRepository $memberRepository;

    public function __construct(
        BookRepository $bookRepository,
        BookIssueRepository $bookIssueRepository,
        LibraryMemberRepository $memberRepository
    ) {
        parent::__construct();
        $this->bookRepository = $bookRepository;
        $this->bookIssueRepository = $bookIssueRepository;
        $this->memberRepository = $memberRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->bookRepository;
    }

    public function addBook(array $data): Book
    {
        return DB::transaction(function () use ($data) {
            $data['available_quantity'] = $data['available_quantity'] ?? $data['total_quantity'] ?? 0;
            $book = $this->bookRepository->create($data);
            $this->logActivity('book_added', $book);
            return $book;
        });
    }

    public function issueBook(int $bookId, int $memberId, int $days = 14): \App\Models\Library\BookIssue
    {
        return DB::transaction(function () use ($bookId, $memberId, $days) {
            $book = $this->bookRepository->getById($bookId);

            if ($book->available_quantity <= 0) {
                throw new ServiceException("Book is not available for issue.");
            }

            $member = $this->memberRepository->getById($memberId);
            if (!$member->is_active) {
                throw new ServiceException("Library membership is not active.");
            }

            $issue = $this->bookIssueRepository->issueBook($bookId, $memberId, $days);

            $this->bookRepository->updateQuantity($bookId, -1);

            $this->logActivity('book_issued', $issue);

            return $issue;
        });
    }

    public function returnBook(int $issueId): \App\Models\Library\BookIssue
    {
        return DB::transaction(function () use ($issueId) {
            $issue = $this->bookIssueRepository->getById($issueId);

            if ($issue->status === 'returned') {
                throw new ServiceException("Book has already been returned.");
            }

            $this->bookIssueRepository->returnBook($issueId);

            $this->bookRepository->updateQuantity($issue->book_id, 1);

            $issue = $issue->fresh();

            $this->logActivity('book_returned', $issue);

            return $issue;
        });
    }

    public function calculateFine(int $issueId, float $finePerDay = 5.0): array
    {
        $issue = $this->bookIssueRepository->getById($issueId);

        if ($issue->status === 'returned') {
            return ['fine' => 0, 'days_overdue' => 0, 'message' => 'Book already returned'];
        }

        $dueDate = \Carbon\Carbon::parse($issue->due_date);
        $now = now();

        if ($now->lte($dueDate)) {
            return ['fine' => 0, 'days_overdue' => 0, 'message' => 'Not overdue'];
        }

        $daysOverdue = $dueDate->diffInDays($now);
        $fine = $daysOverdue * $finePerDay;

        return [
            'fine' => round($fine, 2),
            'days_overdue' => $daysOverdue,
            'fine_per_day' => $finePerDay,
        ];
    }

    public function manageMembership(array $data): \App\Models\Library\LibraryMember
    {
        return DB::transaction(function () use ($data) {
            if (!isset($data['member_no'])) {
                $data['member_no'] = 'LBM-' . now()->format('YmdHis') . '-' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
            }
            $data['is_active'] = $data['is_active'] ?? true;

            $member = $this->memberRepository->create($data);

            $this->logActivity('library_membership_managed', $member);

            return $member;
        });
    }

    public function getLibraryReport(): array
    {
        $totalBooks = $this->bookRepository->getAll()->count();
        $availableBooks = $this->bookRepository->getAvailableBooks()->count();
        $issuedBooks = $this->bookIssueRepository->getIssuedBooks()->count();
        $overdueBooks = $this->bookIssueRepository->getOverdueBooks()->count();
        $activeMembers = $this->memberRepository->getActiveMembers()->count();

        $report = [
            'total_books' => $totalBooks,
            'available_books' => $availableBooks,
            'issued_books' => $issuedBooks,
            'overdue_books' => $overdueBooks,
            'active_members' => $activeMembers,
        ];

        $this->logActivity('library_report_viewed', $report);

        return $report;
    }
}
