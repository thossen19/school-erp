<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\StoreJournalEntryRequest;
use App\Models\Accounting\JournalEntry;
use App\Services\Accounting\AccountingService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountingController extends Controller
{
    use ApiResponseTrait;

    protected AccountingService $accountingService;

    public function __construct(AccountingService $accountingService)
    {
        $this->accountingService = $accountingService;
    }

    public function index(Request $request): JsonResponse
    {
        $entries = JournalEntry::with('items.account')->when($request->date_from, fn($q) => $q->whereDate('entry_date', '>=', $request->date_from))->when($request->date_to, fn($q) => $q->whereDate('entry_date', '<=', $request->date_to))->when($request->reference_type, fn($q) => $q->where('reference_type', $request->reference_type))->orderBy('entry_date', 'desc')->paginate($request->per_page ?? 15);

        return $this->paginatedResponse($entries, 'Journal entries retrieved');
    }

    public function createJournalEntry(StoreJournalEntryRequest $request): JsonResponse
    {
        $entry = $this->accountingService->createJournalEntry($request->validated());
        return $this->createdResponse($entry->load('items.account'), 'Journal entry created');
    }

    public function reconcileBank(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'account_id' => 'required|integer|exists:chart_of_accounts,id',
            'statement_date' => 'required|date',
            'ending_balance' => 'required|numeric',
            'statement_entries' => 'required|array|min:1',
            'statement_entries.*.date' => 'required|date',
            'statement_entries.*.description' => 'required|string|max:255',
            'statement_entries.*.amount' => 'required|numeric',
            'statement_entries.*.type' => 'required|string|in:debit,credit',
        ]);

        $result = $this->accountingService->reconcileBank($request->validated());
        return $this->successResponse($result, 'Bank reconciliation completed');
    }

    public function manageBudget(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fiscal_year' => 'required|string|max:20',
            'budgets' => 'required|array|min:1',
            'budgets.*.account_id' => 'required|integer|exists:chart_of_accounts,id',
            'budgets.*.allocated_amount' => 'required|numeric|min:0',
        ]);

        $budgets = $this->accountingService->manageBudgets($request->fiscal_year, $request->budgets);
        return $this->successResponse($budgets, 'Budgets managed');
    }

    public function getStatements(Request $request): JsonResponse
    {
        $request->validate([
            'account_id' => 'required|integer|exists:chart_of_accounts,id',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        $statements = $this->accountingService->getAccountStatements(
            $request->account_id,
            $request->date_from,
            $request->date_to
        );
        return $this->successResponse($statements, 'Account statements');
    }

    public function exportTally(Request $request): JsonResponse
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
        ]);

        $export = $this->accountingService->exportToTally($request->date_from, $request->date_to);
        return $this->successResponse($export, 'Tally export generated');
    }
}
