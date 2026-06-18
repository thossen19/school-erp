@extends('layouts.app')
@section('title', 'Cash Book')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-money-bill me-2"></i>Cash Book</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Accounting</li><li class="breadcrumb-item active">Cash Book</li></ol></nav>
    </div>
</div>
@if(!$cashAccount)
<div class="alert alert-warning">No cash account found. Create a cash account in Chart of Accounts first.</div>
@else
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4"><strong>Cash Account:</strong> {{ $cashAccount->code }} - {{ $cashAccount->name }}</div>
            <div class="col-md-4"><strong>Current Balance:</strong> ৳{{ number_format($cashAccount->balance ?? 0, 2) }}</div>
        </div>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Date','Reference','Description','Receipts (৳)','Payments (৳)']">
            @php $receiptsTotal = 0; $paymentsTotal = 0; @endphp
            @forelse($entries as $e)
                @php $receiptsTotal += $e->debit; $paymentsTotal += $e->credit; @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($e->date)->format('d-m-Y') }}</td>
                    <td class="font-monospace">{{ $e->entry_number ?? $e->journal_entry_id ?? $e->id }}</td>
                    <td>{{ $e->line_description ?? $e->description ?? '-' }}</td>
                    <td class="text-end @if($e->debit > 0) fw-bold text-success @endif">{{ $e->debit > 0 ? number_format($e->debit, 2) : '-' }}</td>
                    <td class="text-end @if($e->credit > 0) fw-bold text-danger @endif">{{ $e->credit > 0 ? number_format($e->credit, 2) : '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-3">No cash entries found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$entries" />
    <div class="card-footer">
        <div class="row fw-bold">
            <div class="col-md-8"></div>
            <div class="col-md-2 text-end text-success">৳{{ number_format($receiptsTotal, 2) }}</div>
            <div class="col-md-2 text-end text-danger">৳{{ number_format($paymentsTotal, 2) }}</div>
        </div>
    </div>
</div>
@endif
@endsection
