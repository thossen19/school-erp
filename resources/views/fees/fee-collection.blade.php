@extends('layouts.app')
@section('title', 'Fee Collection')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-hand-holding-usd me-2"></i>Fee Collection</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Fees</a></li><li class="breadcrumb-item active">Fee Collection</li></ol></nav>
    </div>
    <div><a href="{{ route('fees.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Collect Fee</a></div>
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
                    <option value="partial" {{ request('status')=='partial'?'selected':'' }}>Partial</option>
                    <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                </select>
            </div>
            <div class="col-auto">
                <select name="payment_mode" class="form-select form-select-sm">
                    <option value="">All Modes</option>
                    <option value="cash" {{ request('payment_mode')=='cash'?'selected':'' }}>Cash</option>
                    <option value="cheque" {{ request('payment_mode')=='cheque'?'selected':'' }}>Cheque</option>
                    <option value="online" {{ request('payment_mode')=='online'?'selected':'' }}>Online</option>
                </select>
            </div>
            <div class="col-auto"><button type="submit" class="btn btn-sm btn-outline-primary">Filter</button></div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Receipt','Student','Admission No','Amount','Paid','Balance','Date','Method','Status','Action']">
            @foreach($collections as $c)
            <tr>
                <td><code>{{ $c->receipt_number ?? $c->receipt_no ?? 'N/A' }}</code></td>
                <td>{{ $c->first_name }} {{ $c->last_name }}</td>
                <td>{{ $c->admission_no ?? '-' }}</td>
                <td>{{ number_format($c->total_amount, 2) }}</td>
                <td>{{ number_format($c->paid_amount, 2) }}</td>
                <td class="text-{{ $c->balance_amount > 0 ? 'danger' : 'success' }} fw-bold">{{ number_format($c->balance_amount, 2) }}</td>
                <td>{{ $c->payment_date }}</td>
                <td>{{ $c->payment_mode ?? $c->payment_method ?? '-' }}</td>
                <td><span class="badge bg-{{ $c->status=='paid'?'success':($c->status=='partial'?'warning text-dark':'secondary') }}">{{ ucfirst($c->status) }}</span></td>
                <td><a href="{{ route('fees.print-receipt', $c->id) }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-print"></i></a></td>
            </tr>
            @endforeach
            @if($collections->isEmpty())<tr><td colspan="10" class="text-center text-muted py-3">No collections found</td></tr>@endif
        </x-table>
    </div>
    <x-pagination :paginator="$collections" />
</div>
@endsection
