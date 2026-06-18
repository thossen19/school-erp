<?php

namespace App\Http\Requests\Accounting;

use Illuminate\Foundation\Http\FormRequest;

class StoreJournalEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'entry_no' => 'required|string|max:50|unique:journal_entries,entry_no',
            'entry_date' => 'required|date',
            'description' => 'nullable|string|max:500',
            'reference_type' => 'nullable|string|max:100',
            'reference_id' => 'nullable|integer',
            'items' => 'required|array|min:2',
            'items.*.account_id' => 'required|integer|exists:chart_of_accounts,id',
            'items.*.type' => 'required|string|in:debit,credit',
            'items.*.amount' => 'required|numeric|min:0',
            'items.*.description' => 'nullable|string|max:500',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $items = $this->input('items', []);
            $totalDebit = 0;
            $totalCredit = 0;
            foreach ($items as $item) {
                if ($item['type'] === 'debit') $totalDebit += $item['amount'];
                else $totalCredit += $item['amount'];
            }
            if (abs($totalDebit - $totalCredit) > 0.01) {
                $validator->errors()->add('items', 'Total debits must equal total credits.');
            }
        });
    }
}
