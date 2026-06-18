@extends('layouts.app')
@section('title', 'Accounting Report')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-file-alt me-2"></i>Accounting Report</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Accounting</li><li class="breadcrumb-item active">Report</li></ol></nav>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-3 col-6"><div class="card shadow-sm border-0 text-center p-3 bg-primary bg-opacity-10"><i class="fas fa-book fa-2x text-primary mb-2"></i><h6>Total Accounts</h6><h4 class="fw-bold">{{ $totalAccounts }}</h4></div></div>
    <div class="col-md-3 col-6"><div class="card shadow-sm border-0 text-center p-3 bg-success bg-opacity-10"><i class="fas fa-pen-alt fa-2x text-success mb-2"></i><h6>Journal Entries</h6><h4 class="fw-bold">{{ $totalEntries }}</h4></div></div>
    <div class="col-md-3 col-6"><div class="card shadow-sm border-0 text-center p-3 bg-warning bg-opacity-10"><i class="fas fa-arrow-right fa-2x text-warning mb-2"></i><h6>Payables</h6><h4 class="fw-bold">৳{{ number_format($totalPayables, 2) }}</h4><small class="text-muted">{{ $pendingPayables }} pending</small></div></div>
    <div class="col-md-3 col-6"><div class="card shadow-sm border-0 text-center p-3 bg-info bg-opacity-10"><i class="fas fa-arrow-left fa-2x text-info mb-2"></i><h6>Receivables</h6><h4 class="fw-bold">৳{{ number_format($totalReceivables, 2) }}</h4><small class="text-muted">{{ $pendingReceivables }} pending</small></div></div>
    <div class="col-md-4"><div class="card shadow-sm border-0 text-center p-3 bg-danger bg-opacity-10"><i class="fas fa-chart-pie fa-2x text-danger mb-2"></i><h6>Budget Allocated</h6><h4 class="fw-bold">৳{{ number_format($totalBudget, 2) }}</h4></div></div>
    <div class="col-md-4"><div class="card shadow-sm border-0 text-center p-3 bg-secondary bg-opacity-10"><i class="fas fa-wallet fa-2x text-secondary mb-2"></i><h6>Budget Spent</h6><h4 class="fw-bold">৳{{ number_format($totalSpent, 2) }}</h4></div></div>
    <div class="col-md-4"><div class="card shadow-sm border-0 text-center p-3 bg-success bg-opacity-10"><i class="fas fa-piggy-bank fa-2x text-success mb-2"></i><h6>Budget Remaining</h6><h4 class="fw-bold">৳{{ number_format($totalBudget - $totalSpent, 2) }}</h4></div></div>
</div>
@endsection
