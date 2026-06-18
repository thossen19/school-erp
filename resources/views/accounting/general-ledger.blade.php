@extends('layouts.app')
@section('title', 'General Ledger')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-book me-2"></i>General Ledger</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Accounting</li><li class="breadcrumb-item active">General Ledger</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Account</label>
                <select name="account_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Select Account --</option>
                    @foreach($accounts as $a)
                        <option value="{{ $a->id }}" {{ $selectedAccountId == $a->id ? 'selected' : '' }}>{{ $a->code }} - {{ $a->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                @if($selectedAccountId)
                    <a href="{{ route('accounting.general-ledger') }}" class="btn btn-outline-secondary"><i class="fas fa-times"></i> Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>
@if($selectedAccountId)
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Date','Entry #','Description','Debit (৳)','Credit (৳)','Balance']">
            @forelse($entries as $e)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($e->date)->format('d-m-Y') }}</td>
                    <td class="font-monospace">{{ $e->entry_number ?? $e->journal_entry_id ?? $e->id }}</td>
                    <td>{{ $e->line_description ?? $e->description ?? '-' }}</td>
                    <td class="text-end">{{ $e->debit > 0 ? number_format($e->debit, 2) : '-' }}</td>
                    <td class="text-end">{{ $e->credit > 0 ? number_format($e->credit, 2) : '-' }}</td>
                    <td class="text-end fw-bold">{{ number_format($e->debit - $e->credit, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-3">No entries for this account.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$entries" />
</div>
@else
<div class="card shadow-sm border-0">
    <div class="card-body text-center text-muted py-5">
        <i class="fas fa-hand-pointer fa-3x mb-3 d-block"></i>
        <p>Select an account above to view its ledger entries.</p>
    </div>
</div>
@endif
@endsection
