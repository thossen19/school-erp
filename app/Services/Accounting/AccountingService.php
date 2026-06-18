<?php

namespace App\Services\Accounting;

use App\Contracts\RepositoryInterface;
use App\Models\Accounting\JournalEntry;
use App\Repositories\Accounting\JournalEntryRepository;
use App\Repositories\Accounting\BankReconciliationRepository;
use App\Repositories\Accounting\BudgetRepository;
use App\Repositories\Accounting\ChartOfAccountRepository;
use App\Services\BaseService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AccountingService extends BaseService
{
    protected JournalEntryRepository $journalEntryRepository;
    protected BankReconciliationRepository $bankReconciliationRepository;
    protected BudgetRepository $budgetRepository;
    protected ChartOfAccountRepository $chartOfAccountRepository;

    public function __construct(
        JournalEntryRepository $journalEntryRepository,
        BankReconciliationRepository $bankReconciliationRepository,
        BudgetRepository $budgetRepository,
        ChartOfAccountRepository $chartOfAccountRepository
    ) {
        parent::__construct();
        $this->journalEntryRepository = $journalEntryRepository;
        $this->bankReconciliationRepository = $bankReconciliationRepository;
        $this->budgetRepository = $budgetRepository;
        $this->chartOfAccountRepository = $chartOfAccountRepository;
    }

    public function repository(): RepositoryInterface
    {
        return $this->journalEntryRepository;
    }

    public function createJournalEntry(array $data): JournalEntry
    {
        return DB::transaction(function () use ($data) {
            $totalDebit = 0;
            $totalCredit = 0;

            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    $totalDebit += $item['debit'] ?? 0;
                    $totalCredit += $item['credit'] ?? 0;
                }
            }

            if (abs($totalDebit - $totalCredit) > 0.01) {
                throw new \App\Exceptions\ServiceException(
                    "Journal entry is not balanced. Debit: {$totalDebit}, Credit: {$totalCredit}"
                );
            }

            $data['total_debit'] = $totalDebit;
            $data['total_credit'] = $totalCredit;
            $data['status'] = $data['status'] ?? 'draft';

            $entry = $this->journalEntryRepository->create($data);

            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    $item['journal_entry_id'] = $entry->id;
                    \App\Models\Accounting\JournalEntryItem::create($item);
                }
            }

            $this->logActivity('journal_entry_created', $entry);

            return $entry;
        });
    }

    public function reconcileBank(int $accountId, array $data): \App\Models\Accounting\BankReconciliation
    {
        return DB::transaction(function () use ($accountId, $data) {
            $reconciliation = $this->bankReconciliationRepository->create([
                'chart_of_account_id' => $accountId,
                'bank_balance' => $data['bank_balance'],
                'book_balance' => $data['book_balance'],
                'difference' => $data['bank_balance'] - $data['book_balance'],
                'reconciliation_date' => $data['reconciliation_date'] ?? now(),
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
            ]);

            $this->logActivity('bank_reconciliation_created', $reconciliation);

            return $reconciliation;
        });
    }

    public function manageBudget(array $data): \App\Models\Accounting\Budget
    {
        return DB::transaction(function () use ($data) {
            $budget = $this->budgetRepository->create($data);

            $this->logActivity('budget_managed', $budget);

            return $budget;
        });
    }

    public function generateFinancialStatement(string $type, string $startDate, string $endDate): array
    {
        $entries = $this->journalEntryRepository->getByDateRange($startDate, $endDate);

        $statement = match ($type) {
            'balance_sheet' => $this->generateBalanceSheet($startDate, $endDate),
            'income_statement' => $this->generateIncomeStatement($startDate, $endDate),
            'cash_flow' => $this->generateCashFlowStatement($startDate, $endDate),
            'trial_balance' => $this->generateTrialBalance($startDate, $endDate),
            default => throw new \App\Exceptions\ServiceException("Unsupported financial statement type: {$type}"),
        };

        $this->logActivity('financial_statement_generated', ['type' => $type]);

        return $statement;
    }

    public function exportTally(string $startDate, string $endDate): array
    {
        $entries = $this->journalEntryRepository->getByDateRange($startDate, $endDate);

        $tallyData = [
            'export_date' => now()->format('Y-m-d H:i:s'),
            'period' => ['from' => $startDate, 'to' => $endDate],
            'total_entries' => $entries->count(),
            'total_debit' => $entries->sum('total_debit'),
            'total_credit' => $entries->sum('total_credit'),
            'entries' => $entries->map(fn($e) => [
                'date' => $e->entry_date,
                'reference' => $e->reference_number,
                'description' => $e->description,
                'debit' => $e->total_debit,
                'credit' => $e->total_credit,
            ]),
        ];

        $this->logActivity('tally_exported_accounting', ['period' => "{$startDate} to {$endDate}"]);

        return $tallyData;
    }

    private function generateBalanceSheet(string $startDate, string $endDate): array
    {
        $assets = $this->chartOfAccountRepository->getAssetAccounts();
        $liabilities = $this->chartOfAccountRepository->getLiabilityAccounts();

        $totalAssets = $assets->sum(fn($a) => $a->balance ?? 0);
        $totalLiabilities = $liabilities->sum(fn($l) => $l->balance ?? 0);
        $equity = $totalAssets - $totalLiabilities;

        return [
            'type' => 'balance_sheet',
            'as_of' => $endDate,
            'total_assets' => $totalAssets,
            'total_liabilities' => $totalLiabilities,
            'equity' => $equity,
            'assets' => $assets,
            'liabilities' => $liabilities,
        ];
    }

    private function generateIncomeStatement(string $startDate, string $endDate): array
    {
        $income = $this->chartOfAccountRepository->getIncomeAccounts();
        $expenses = $this->chartOfAccountRepository->getExpenseAccounts();

        $totalIncome = $income->sum(fn($a) => $a->balance ?? 0);
        $totalExpenses = $expenses->sum(fn($a) => $a->balance ?? 0);

        return [
            'type' => 'income_statement',
            'period' => ['from' => $startDate, 'to' => $endDate],
            'total_income' => $totalIncome,
            'total_expenses' => $totalExpenses,
            'net_income' => $totalIncome - $totalExpenses,
        ];
    }

    private function generateCashFlowStatement(string $startDate, string $endDate): array
    {
        $entries = $this->journalEntryRepository->getByDateRange($startDate, $endDate);

        $operating = $entries->filter(fn($e) => $e->entry_type === 'operating');
        $investing = $entries->filter(fn($e) => $e->entry_type === 'investing');
        $financing = $entries->filter(fn($e) => $e->entry_type === 'financing');

        return [
            'type' => 'cash_flow',
            'period' => ['from' => $startDate, 'to' => $endDate],
            'operating_activities' => $operating->sum('total_debit') - $operating->sum('total_credit'),
            'investing_activities' => $investing->sum('total_debit') - $investing->sum('total_credit'),
            'financing_activities' => $financing->sum('total_debit') - $financing->sum('total_credit'),
            'net_cash_flow' => $entries->sum('total_debit') - $entries->sum('total_credit'),
        ];
    }

    private function generateTrialBalance(string $startDate, string $endDate): array
    {
        $accounts = $this->chartOfAccountRepository->getActiveAccounts();
        $entries = $this->journalEntryRepository->getByDateRange($startDate, $endDate);

        $trialBalance = $accounts->map(function ($account) use ($entries) {
            $accountEntries = $entries->filter(function ($entry) use ($account) {
                return $entry->items && $entry->items->contains('chart_of_account_id', $account->id);
            });

            return [
                'account' => $account->name,
                'code' => $account->code,
                'debit' => $accountEntries->sum('total_debit'),
                'credit' => $accountEntries->sum('total_credit'),
            ];
        });

        return [
            'type' => 'trial_balance',
            'as_of' => $endDate,
            'accounts' => $trialBalance,
            'total_debit' => $trialBalance->sum('debit'),
            'total_credit' => $trialBalance->sum('credit'),
        ];
    }
}
