@extends('layouts.app')
@section('title', 'Financial Reports')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chart-pie me-2"></i>Financial Reports</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Fees</a></li><li class="breadcrumb-item active">Financial Reports</li></ol></nav>
    </div>
</div>
<div class="row g-2 mb-3">
    <div class="col-md-2"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-success">{{ number_format($totalCollected, 2) }}</h5><small class="text-muted">Collected</small></div></div>
    <div class="col-md-2"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-danger">{{ number_format($totalPending, 2) }}</h5><small class="text-muted">Pending</small></div></div>
    <div class="col-md-2"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-warning">{{ number_format($totalFineCollected, 2) }}</h5><small class="text-muted">Fines</small></div></div>
    <div class="col-md-2"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-info">{{ number_format($totalDiscountGiven, 2) }}</h5><small class="text-muted">Discounts</small></div></div>
    <div class="col-md-2"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-primary">{{ $totalTransactions }}</h5><small class="text-muted">Transactions</small></div></div>
</div>
<div class="row g-2">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-calendar me-2"></i>Monthly Collection (Last 12 Months)</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Month','Amount']">
                    @foreach($monthlyCollection as $m)
                    <tr><td>{{ $m->month }}</td><td class="fw-bold text-success">{{ number_format($m->total, 2) }}</td></tr>
                    @endforeach
                    @if($monthlyCollection->isEmpty())<tr><td colspan="2" class="text-center text-muted py-3">No data</td></tr>@endif
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-chart-bar me-2"></i>Payment Method Breakdown</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Method','Transactions','Amount']">
                    @foreach($methodBreakdown as $m)
                    <tr><td><span class="badge bg-secondary">{{ ucfirst($m->payment_method ?? 'Unknown') }}</span></td><td>{{ $m->total }}</td><td class="fw-bold">{{ number_format($m->amount, 2) }}</td></tr>
                    @endforeach
                    @if($methodBreakdown->isEmpty())<tr><td colspan="3" class="text-center text-muted py-3">No data</td></tr>@endif
                </x-table>
            </div>
        </div>
    </div>
</div>
@endsection
