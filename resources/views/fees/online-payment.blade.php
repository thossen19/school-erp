@extends('layouts.app')
@section('title', 'Online Payment')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-globe me-2"></i>Online Payment</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Fees</a></li><li class="breadcrumb-item active">Online Payment</li></ol></nav>
    </div>
</div>
<div class="row g-2 mb-3">
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-primary">{{ $totalOnline }}</h5><small class="text-muted">Online Transactions</small></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-success">{{ number_format($totalAmount, 2) }}</h5><small class="text-muted">Total Online Amount</small></div></div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-auto"><input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}"></div>
            <div class="col-auto"><input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}"></div>
            <div class="col-auto">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="paid" {{ request('status')=='paid'?'selected':'' }}>Paid</option>
                    <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                </select>
            </div>
            <div class="col-auto"><button type="submit" class="btn btn-sm btn-outline-primary">Filter</button></div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Transaction ID','Student','Amount','Method','Date','Status']">
            @foreach($payments as $p)
            <tr>
                <td><code>{{ Str::limit($p->transaction_id ?? 'N/A', 20) }}</code></td>
                <td>{{ $p->first_name }} {{ $p->last_name }}</td>
                <td>{{ number_format($p->paid_amount, 2) }}</td>
                <td><span class="badge bg-info">{{ $p->payment_method ?? $p->payment_mode ?? '-' }}</span></td>
                <td>{{ $p->payment_date }}</td>
                <td><span class="badge bg-{{ $p->status=='paid'?'success':'warning' }}">{{ ucfirst($p->status) }}</span></td>
            </tr>
            @endforeach
            @if($payments->isEmpty())<tr><td colspan="6" class="text-center text-muted py-3">No online payments found</td></tr>@endif
        </x-table>
    </div>
    <x-pagination :paginator="$payments" />
</div>
@endsection
