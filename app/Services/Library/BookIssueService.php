<?php

namespace App\Services\Library;

use App\Contracts\RepositoryInterface;
use App\Exceptions\ServiceException;
use App\Models\Library\BookIssue;
use App\Repositories\Library\BookIssueRepository;
use App\Repositories\Library\BookRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class BookIssueService extends BaseService
{
    protected BookIssueRepository $bookIssueRepository;
    protected BookRepository $bookRepository;

    public function __construct(
        BookIssueRepository $bookIssueRepository,
        BookRepository $bookRepository
    ) {
        parent::__construct();
        $this->bookIssueRepository = $bookIssueRepository;
        $this->bookRepository = $bookRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->bookIssueRepository;
    }

    public function issue(int $bookId, int $memberId, int $days = 14): BookIssue
    {
        return DB::transaction(function () use ($bookId, $memberId, $days) {
            $book = $this->bookRepository->getById($bookId);

            if ($book->available_quantity <= 0) {
                throw new ServiceException("Book is not available for issue.");
            }

            $issue = $this->bookIssueRepository->issueBook($bookId, $memberId, $days);
            $this->bookRepository->updateQuantity($bookId, -1);

            $this->logActivity('book_issue_created', $issue);

            return $issue;
        });
    }

    public function renew(int $issueId, int $extraDays = 7): BookIssue
    {
        return DB::transaction(function () use ($issueId, $extraDays) {
            $issue = $this->bookIssueRepository->getById($issueId);

            if ($issue->status !== 'issued') {
                throw new ServiceException("Book is not currently issued.");
            }

            $this->bookIssueRepository->renewBook($issueId, $extraDays);

            $issue = $issue->fresh();

            $this->logActivity('book_renewed', $issue);

            return $issue;
        });
    }

    public function return(int $issueId): BookIssue
    {
        return DB::transaction(function () use ($issueId) {
            $issue = $this->bookIssueRepository->getById($issueId);

            if ($issue->status === 'returned') {
                throw new ServiceException("Book has already been returned.");
            }

            $this->bookIssueRepository->returnBook($issueId);
            $this->bookRepository->updateQuantity($issue->book_id, 1);

            $issue = $issue->fresh();

            $this->logActivity('book_returned_via_issue', $issue);

            return $issue;
        });
    }

    public function calculateFine(int $issueId, float $finePerDay = 5.0): array
    {
        $issue = $this->bookIssueRepository->getById($issueId);

        $dueDate = \Carbon\Carbon::parse($issue->due_date);
        $returnDate = $issue->return_date ? \Carbon\Carbon::parse($issue->return_date) : now();

        if ($returnDate->lte($dueDate)) {
            return ['fine' => 0, 'days_overdue' => 0, 'message' => 'Returned on time'];
        }

        $daysOverdue = $dueDate->diffInDays($returnDate);
        $fine = $daysOverdue * $finePerDay;

        return [
            'fine' => round($fine, 2),
            'days_overdue' => $daysOverdue,
            'fine_per_day' => $finePerDay,
        ];
    }

    public function sendDueReminder(int $issueId): bool
    {
        try {
            $issue = $this->bookIssueRepository->getById($issueId);

            activity()->causedBy(auth()->user())->performedOn($issue)->withProperties([
                    'book_id' => $issue->book_id,
                    'member_id' => $issue->member_id,
                    'due_date' => $issue->due_date->format('Y-m-d'),
                    'sent_at' => now(),
                ])->event('due_reminder')->log("BookIssueService: Due reminder sent for issue {$issueId}");

            return true;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('BookIssueService@sendDueReminder: ' . $e->getMessage());
            return false;
        }
    }
}
