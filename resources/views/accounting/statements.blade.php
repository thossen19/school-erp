@extends('layouts.app')
@section('title', 'Financial Statements')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-file-invoice me-2"></i>Financial Statements</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Accounting</li><li class="breadcrumb-item active">Financial Statements</li></ol></nav>
    </div>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h6>Total Revenue</h6><h4 class="text-success fw-bold">৳{{ number_format($revenue, 2) }}</h4></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h6>Total Expenses</h6><h4 class="text-danger fw-bold">৳{{ number_format($expenses, 2) }}</h4></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h6>Net Income</h6><h4 class="fw-bold text-{{ ($revenue - $expenses) >= 0 ? 'success' : 'danger' }}">৳{{ number_format($revenue - $expenses, 2) }}</h4></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h6>Net Assets</h6><h4 class="fw-bold text-info">৳{{ number_format($assets - $liabilities, 2) }}</h4></div></div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white"><h5 class="mb-0">Income Statement</h5></div>
            <div class="card-body p-0">
                <x-table :headers="['Item','Amount (৳)']">
                    <tr><td>Total Revenue</td><td class="text-end text-success fw-bold">{{ number_format($revenue, 2) }}</td></tr>
                    <tr><td>Total Expenses</td><td class="text-end text-danger fw-bold">{{ number_format($expenses, 2) }}</td></tr>
                    <tr class="table-active"><td class="fw-bold">Net {{ ($revenue - $expenses) >= 0 ? 'Income' : 'Loss' }}</td><td class="text-end fw-bold text-{{ ($revenue - $expenses) >= 0 ? 'success' : 'danger' }}">{{ number_format($revenue - $expenses, 2) }}</td></tr>
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white"><h5 class="mb-0">Balance Sheet</h5></div>
            <div class="card-body p-0">
                <x-table :headers="['Item','Amount (৳)']">
                    <tr><td>Assets</td><td class="text-end fw-bold">{{ number_format($assets, 2) }}</td></tr>
                    <tr><td>Liabilities</td><td class="text-end fw-bold">{{ number_format($liabilities, 2) }}</td></tr>
                    <tr><td>Equity</td><td class="text-end fw-bold">{{ number_format($equity, 2) }}</td></tr>
                    <tr class="table-active"><td class="fw-bold">Total (A - L + E)</td><td class="text-end fw-bold">{{ number_format($assets - $liabilities + $equity, 2) }}</td></tr>
                </x-table>
            </div>
        </div>
    </div>
</div>
@endsection
