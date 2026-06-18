@extends('layouts.app')
@section('title', 'Accounts Receivable')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-arrow-left me-2"></i>Accounts Receivable</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Accounting</li><li class="breadcrumb-item active">Accounts Receivable</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                @if(request('status'))
                    <a href="{{ route('accounting.receivable') }}" class="btn btn-outline-secondary"><i class="fas fa-times"></i> Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Student','Admission No','Amount (৳)','Due Date','Balance (৳)','Paid (৳)','Status']">
            @forelse($receivables as $r)
                <tr>
                    <td>{{ $r->first_name ?? '' }} {{ $r->last_name ?? '' }}</td>
                    <td class="font-monospace">{{ $r->admission_no ?? '-' }}</td>
                    <td class="text-end">{{ number_format($r->amount, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->due_date)->format('d-m-Y') }}</td>
                    <td class="text-end fw-bold">{{ number_format($r->balance, 2) }}</td>
                    <td class="text-end">{{ number_format($r->paid_amount ?? 0, 2) }}</td>
                    <td>
                        @php
                            $badge = ['pending' => 'warning', 'paid' => 'success', 'overdue' => 'danger', 'cancelled' => 'secondary'];
                        @endphp
                        <span class="badge bg-{{ $badge[$r->status] ?? 'secondary' }}">{{ ucfirst($r->status) }}</span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-3">No receivables found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$receivables" />
</div>
@endsection
