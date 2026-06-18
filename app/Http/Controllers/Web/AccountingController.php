<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AccountingController extends Controller
{
    public function chartOfAccounts()
    {
        $accounts = DB::table('chart_of_accounts')->orderBy('code')->paginate(20);
        return view('accounting.chart-of-accounts', compact('accounts'));
    }

    public function generalLedger(Request $request)
    {
        $accounts = DB::table('chart_of_accounts')->orderBy('code')->get();
        $selectedAccountId = $request->account_id;

        $entries = collect();
        if ($selectedAccountId) {
            $entries = DB::table('journal_entries')
                ->join('journal_entry_items', 'journal_entries.id', '=', 'journal_entry_items.journal_entry_id')
                ->select('journal_entries.*', 'journal_entry_items.debit', 'journal_entry_items.credit', 'journal_entry_items.description as line_description')
                ->where('journal_entry_items.account_id', $selectedAccountId)
                ->orderBy('journal_entries.date', 'desc')
                ->paginate(20);
        }

        return view('accounting.general-ledger', compact('accounts', 'entries', 'selectedAccountId'));
    }

    public function journalEntry(Request $request)
    {
        $entries = DB::table('journal_entries')
            ->leftJoin('journal_entry_items', 'journal_entries.id', '=', 'journal_entry_items.journal_entry_id')
            ->select('journal_entries.*',
                DB::raw('COALESCE(SUM(journal_entry_items.debit), 0) as total_debit'),
                DB::raw('COALESCE(SUM(journal_entry_items.credit), 0) as total_credit'))
            ->groupBy('journal_entries.id', 'journal_entries.school_id', 'journal_entries.entry_number', 'journal_entries.date', 'journal_entries.description', 'journal_entries.reference_type', 'journal_entries.reference_id', 'journal_entries.created_by', 'journal_entries.status', 'journal_entries.created_at', 'journal_entries.updated_at')
            ->orderBy('journal_entries.date', 'desc')
            ->paginate(20);

        return view('accounting.journal-entry', compact('entries'));
    }

    public function payable(Request $request)
    {
        $payables = DB::table('accounts_payable')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderBy('due_date', 'asc')
            ->paginate(20);

        return view('accounting.payable', compact('payables'));
    }

    public function receivable(Request $request)
    {
        $receivables = DB::table('accounts_receivable')
            ->leftJoin('students', 'accounts_receivable.student_id', '=', 'students.id')
            ->select('accounts_receivable.*', 'students.first_name', 'students.last_name', 'students.admission_no')
            ->when($request->status, fn($q) => $q->where('accounts_receivable.status', $request->status))
            ->orderBy('accounts_receivable.due_date', 'asc')
            ->paginate(20);

        return view('accounting.receivable', compact('receivables'));
    }

    public function trialBalance()
    {
        $accounts = DB::table('chart_of_accounts')
            ->orderBy('code')
            ->get();

        return view('accounting.trial-balance', compact('accounts'));
    }

    public function cashBook(Request $request)
    {
        $cashAccount = DB::table('chart_of_accounts')->where('type', 'asset')->where('name', 'like', '%cash%')->first();

        $entries = collect();
        if ($cashAccount) {
            $entries = DB::table('journal_entries')
                ->join('journal_entry_items', 'journal_entries.id', '=', 'journal_entry_items.journal_entry_id')
                ->select('journal_entries.*', 'journal_entry_items.debit', 'journal_entry_items.credit', 'journal_entry_items.description as line_description')
                ->where('journal_entry_items.account_id', $cashAccount->id)
                ->orderBy('journal_entries.date', 'desc')
                ->paginate(20);
        }

        return view('accounting.cash-book', compact('entries', 'cashAccount'));
    }

    public function bankReconciliation(Request $request)
    {
        $reconciliations = DB::table('bank_reconciliations')
            ->leftJoin('chart_of_accounts', 'bank_reconciliations.account_id', '=', 'chart_of_accounts.id')
            ->select('bank_reconciliations.*', 'chart_of_accounts.name as account_name', 'chart_of_accounts.code as account_code')
            ->orderBy('bank_reconciliations.statement_date', 'desc')
            ->paginate(20);

        $reconciliations->each(function ($r) {
            $r->book_balance = $r->system_balance;
            $r->status = $r->reconciled ? 'matched' : 'pending';
        });

        return view('accounting.bank-reconciliation', compact('reconciliations'));
    }

    public function budget(Request $request)
    {
        $budgets = DB::table('budgets')
            ->leftJoin('chart_of_accounts', 'budgets.account_id', '=', 'chart_of_accounts.id')
            ->leftJoin('academic_years', 'budgets.academic_year_id', '=', 'academic_years.id')
            ->select('budgets.*', 'chart_of_accounts.name as account_name', 'chart_of_accounts.code as account_code', 'academic_years.name as fiscal_year')
            ->orderBy('budgets.created_at', 'desc')
            ->paginate(20);

        return view('accounting.budget', compact('budgets'));
    }

    public function statements()
    {
        $revenue = DB::table('chart_of_accounts')->where('type', 'revenue')->sum('balance');
        $expenses = DB::table('chart_of_accounts')->where('type', 'expense')->sum('balance');
        $assets = DB::table('chart_of_accounts')->where('type', 'asset')->sum('balance');
        $liabilities = DB::table('chart_of_accounts')->where('type', 'liability')->sum('balance');
        $equity = DB::table('chart_of_accounts')->where('type', 'equity')->sum('balance');

        return view('accounting.statements', compact('revenue', 'expenses', 'assets', 'liabilities', 'equity'));
    }

    public function currencies(Request $request)
    {
        $schoolId = 1;

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'code' => 'required|string|max:10|unique:currencies,code,' . $request->edit_id,
                'symbol' => 'required|string|max:10',
                'name' => 'required|string|max:100',
                'hundreds_name' => 'required|string|max:50',
                'country' => 'nullable|string|max:100',
                'auto_update' => 'nullable|boolean',
            ]);

            $data = [
                'school_id' => $schoolId,
                'code' => strtoupper($validated['code']),
                'symbol' => $validated['symbol'],
                'name' => $validated['name'],
                'hundreds_name' => $validated['hundreds_name'],
                'country' => $validated['country'] ?? null,
                'auto_update' => $request->boolean('auto_update'),
            ];

            if ($request->edit_id) {
                DB::table('currencies')->where('id', $request->edit_id)->update($data);
                return redirect()->route('accounting.currencies')->with('success', 'Currency updated successfully.');
            } else {
                DB::table('currencies')->insert($data);
                return redirect()->route('accounting.currencies')->with('success', 'Currency added successfully.');
            }
        }

        if ($request->has('delete')) {
            $id = $request->delete;
            $currency = DB::table('currencies')->where('id', $id)->first();
            if ($currency) {
                if ($currency->is_default) {
                    return redirect()->route('accounting.currencies')->with('error', 'Cannot delete the default/home currency.');
                }
                $inUse = DB::table('exchange_rates')->where('currency_code', $currency->code)->exists();
                if ($inUse) {
                    return redirect()->route('accounting.currencies')->with('error', 'Cannot delete: exchange rates exist for this currency.');
                }
                DB::table('currencies')->where('id', $id)->delete();
                return redirect()->route('accounting.currencies')->with('success', 'Currency deleted successfully.');
            }
        }

        if ($request->has('set_default')) {
            $id = $request->set_default;
            DB::table('currencies')->where('school_id', $schoolId)->update(['is_default' => false]);
            DB::table('currencies')->where('id', $id)->update(['is_default' => true]);
            return redirect()->route('accounting.currencies')->with('success', 'Default currency changed successfully.');
        }

        $currencies = DB::table('currencies')->where('school_id', $schoolId)->orderBy('code')->get();
        $editCurrency = null;
        if ($request->has('edit')) {
            $editCurrency = DB::table('currencies')->where('id', $request->edit)->first();
        }

        $defaultCurrency = DB::table('currencies')->where('school_id', $schoolId)->where('is_default', true)->first();

        return view('accounting.currencies', compact('currencies', 'editCurrency', 'defaultCurrency'));
    }

    public function exchangeRates(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'currency_code' => 'required|string|max:10|exists:currencies,code',
                'rate' => 'required|numeric|min:0',
                'date' => 'required|date',
                'source' => 'nullable|string|max:50',
            ]);

            DB::table('exchange_rates')->updateOrInsert(
                ['currency_code' => $validated['currency_code'], 'date' => $validated['date']],
                ['rate' => $validated['rate'], 'source' => $validated['source'] ?? null, 'updated_at' => now()]
            );

            return redirect()->route('accounting.exchange-rates')->with('success', 'Exchange rate saved.');
        }

        if ($request->has('delete')) {
            DB::table('exchange_rates')->where('id', $request->delete)->delete();
            return redirect()->route('accounting.exchange-rates')->with('success', 'Exchange rate deleted.');
        }

        $rates = DB::table('exchange_rates')->orderBy('date', 'desc')->paginate(20);
        $currencies = DB::table('currencies')->where('is_active', true)->orderBy('code')->get();

        return view('accounting.exchange-rates', compact('rates', 'currencies'));
    }

    public function report()
    {
        $totalAccounts = DB::table('chart_of_accounts')->count();
        $totalEntries = DB::table('journal_entries')->count();
        $totalPayables = DB::table('accounts_payable')->sum('balance');
        $totalReceivables = DB::table('accounts_receivable')->sum('balance');
        $pendingPayables = DB::table('accounts_payable')->where('status', 'pending')->count();
        $pendingReceivables = DB::table('accounts_receivable')->where('status', 'pending')->count();
        $totalBudget = DB::table('budgets')->sum('allocated_amount');
        $totalSpent = DB::table('budgets')->sum('used_amount');

        return view('accounting.report', compact(
            'totalAccounts', 'totalEntries', 'totalPayables', 'totalReceivables',
            'pendingPayables', 'pendingReceivables', 'totalBudget', 'totalSpent'
        ));
    }
}
