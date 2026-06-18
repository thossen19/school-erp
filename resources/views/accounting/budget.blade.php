@extends('layouts.app')
@section('title', 'Budget Management')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chart-pie me-2"></i>Budget Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Accounting</li><li class="breadcrumb-item active">Budget Management</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Account','Fiscal Year','Allocated (৳)','Used (৳)','Remaining (৳)','Status']">
            @forelse($budgets as $b)
                @php
                    $remaining = $b->allocated_amount - $b->used_amount;
                    $pct = $b->allocated_amount > 0 ? round(($b->used_amount / $b->allocated_amount) * 100) : 0;
                @endphp
                <tr>
                    <td>{{ $b->account_code ?? '' }} - {{ $b->account_name ?? $b->account_id }}</td>
                    <td>{{ $b->fiscal_year }}</td>
                    <td class="text-end">{{ number_format($b->allocated_amount, 2) }}</td>
                    <td class="text-end">{{ number_format($b->used_amount, 2) }}</td>
                    <td class="text-end fw-bold text-{{ $remaining >= 0 ? 'success' : 'danger' }}">{{ number_format($remaining, 2) }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-grow-1" style="height:6px;"><div class="progress-bar bg-{{ $pct > 90 ? 'danger' : ($pct > 70 ? 'warning' : 'success') }}" style="width:{{ min($pct,100) }}%"></div></div>
                            <span class="small fw-bold">{{ $pct }}%</span>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-3">No budgets found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$budgets" />
</div>
@endsection
