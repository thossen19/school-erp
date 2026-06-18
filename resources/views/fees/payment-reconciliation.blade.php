@extends('layouts.app')
@section('title', 'Payment Reconciliation')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-exchange-alt me-2"></i>Payment Reconciliation</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Fees</a></li><li class="breadcrumb-item active">Payment Reconciliation</li></ol></nav>
    </div>
</div>
<div class="row g-2 mb-3">
    @foreach($summary as $s)
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-primary">{{ ucfirst($s->payment_method ?? 'Unknown') }}</h5><small class="text-muted">{{ $s->total_transactions }} txns / {{ number_format($s->total_amount, 2) }}</small></div></div>
    @endforeach
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-auto"><input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}"></div>
            <div class="col-auto"><input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}"></div>
            <div class="col-auto">
                <select name="payment_method" class="form-select form-select-sm">
                    <option value="">All Methods</option>
                    <option value="cash" {{ request('payment_method')=='cash'?'selected':'' }}>Cash</option>
                    <option value="cheque" {{ request('payment_method')=='cheque'?'selected':'' }}>Cheque</option>
                    <option value="online_transfer" {{ request('payment_method')=='online_transfer'?'selected':'' }}>Online Transfer</option>
                    <option value="bank_deposit" {{ request('payment_method')=='bank_deposit'?'selected':'' }}>Bank Deposit</option>
                    <option value="card" {{ request('payment_method')=='card'?'selected':'' }}>Card</option>
                </select>
            </div>
            <div class="col-auto"><button type="submit" class="btn btn-sm btn-outline-primary">Filter</button></div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Receipt','Student','Amount','Method','Transaction ID','Date','Status']">
            @foreach($reconciliations as $r)
            <tr>
                <td><code>{{ $r->receipt_number ?? $r->receipt_no ?? 'N/A' }}</code></td>
                <td>{{ $r->first_name }} {{ $r->last_name }}</td>
                <td>{{ number_format($r->paid_amount, 2) }}</td>
                <td><span class="badge bg-info">{{ $r->payment_method ?? $r->payment_mode ?? '-' }}</span></td>
                <td><code class="small">{{ Str::limit($r->transaction_id ?? 'N/A', 16) }}</code></td>
                <td>{{ $r->payment_date }}</td>
                <td><span class="badge bg-{{ $r->status=='paid'?'success':'warning' }}">{{ ucfirst($r->status) }}</span></td>
            </tr>
            @endforeach
            @if($reconciliations->isEmpty())<tr><td colspan="7" class="text-center text-muted py-3">No records for reconciliation</td></tr>@endif
        </x-table>
    </div>
    <x-pagination :paginator="$reconciliations" />
</div>
@endsection
