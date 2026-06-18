@extends('layouts.app')
@section('title', 'Trial Balance')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-balance-scale me-2"></i>Trial Balance</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Accounting</li><li class="breadcrumb-item active">Trial Balance</li></ol></nav>
    </div>
    <small class="text-muted">As of {{ now()->format('d-m-Y') }}</small>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Code','Account Name','Debit (৳)','Credit (৳)']">
            @php $totalDebit = 0; $totalCredit = 0; @endphp
            @forelse($accounts as $a)
                @php
                    $balance = $a->balance ?? 0;
                    $debit = in_array($a->type, ['asset', 'expense']) ? $balance : 0;
                    $credit = in_array($a->type, ['liability', 'equity', 'revenue']) ? $balance : 0;
                    $totalDebit += $debit; $totalCredit += $credit;
                @endphp
                <tr>
                    <td class="font-monospace">{{ $a->code }}</td>
                    <td>{{ $a->name }}</td>
                    <td class="text-end">{{ $debit > 0 ? number_format($debit, 2) : '-' }}</td>
                    <td class="text-end">{{ $credit > 0 ? number_format($credit, 2) : '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-3">No accounts found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <div class="card-footer">
        <div class="row fw-bold">
            <div class="col-md-6 text-end">Total: ৳{{ number_format($totalDebit, 2) }}</div>
            <div class="col-md-6 text-end">Total: ৳{{ number_format($totalCredit, 2) }}</div>
        </div>
        @if(abs($totalDebit - $totalCredit) > 0.001)
            <div class="alert alert-danger mb-0 mt-2 py-2"><i class="fas fa-exclamation-triangle me-1"></i> Out of balance by ৳{{ number_format(abs($totalDebit - $totalCredit), 2) }}</div>
        @else
            <div class="alert alert-success mb-0 mt-2 py-2"><i class="fas fa-check-circle me-1"></i> Trial balance is balanced.</div>
        @endif
    </div>
</div>
@endsection
