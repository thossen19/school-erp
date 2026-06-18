@extends('layouts.app')
@section('title', 'Bank Reconciliation')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-university me-2"></i>Bank Reconciliation</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Accounting</li><li class="breadcrumb-item active">Bank Reconciliation</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Account','Statement Date','Bank Balance (৳)','Book Balance (৳)','Difference (৳)','Status']">
            @forelse($reconciliations as $r)
                <tr>
                    <td>{{ $r->account_code ?? '' }} - {{ $r->account_name ?? $r->account_id }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->statement_date)->format('d-m-Y') }}</td>
                    <td class="text-end">{{ number_format($r->bank_balance, 2) }}</td>
                    <td class="text-end">{{ number_format($r->book_balance, 2) }}</td>
                    <td class="text-end fw-bold">{{ number_format($r->bank_balance - $r->book_balance, 2) }}</td>
                    <td><span class="badge bg-{{ $r->status === 'matched' ? 'success' : ($r->status === 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($r->status) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-3">No bank reconciliations found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$reconciliations" />
</div>
@endsection
