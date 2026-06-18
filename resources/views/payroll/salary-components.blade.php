@extends('layouts.app')
@section('title', 'Salary Components')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-coins me-2"></i>Salary Components</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Payroll</li><li class="breadcrumb-item active">Salary Components</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Type</label>
                <select name="type" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="earning" {{ request('type') == 'earning' ? 'selected' : '' }}>Earning</option>
                    <option value="deduction" {{ request('type') == 'deduction' ? 'selected' : '' }}>Deduction</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Component name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
            @if(request()->anyFilled(['type','search']))
            <div class="col-12"><a href="{{ route('payroll.salary-components') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear</a></div>
            @endif
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Name</th><th>Type</th><th>Amount</th><th>%</th><th>Taxable</th><th>Order</th></tr>
                </thead>
                <tbody>
                    @forelse($components as $c)
                    <tr>
                        <td>{{ $c->id }}</td>
                        <td class="fw-semibold">{{ $c->name }}</td>
                        <td><span class="badge bg-{{ $c->type == 'earning' ? 'success' : 'danger' }} bg-opacity-10 text-{{ $c->type == 'earning' ? 'success' : 'danger' }}">{{ ucfirst($c->type) }}</span></td>
                        <td>${{ number_format($c->amount ?? $c->value, 2) }}</td>
                        <td>{{ $c->calculation_type == 'percentage' ? $c->value.'%' : '$'.number_format($c->value ?? $c->amount, 2) }}</td>
                        <td>{!! $c->is_taxable ? '<span class="badge bg-warning bg-opacity-10 text-warning">Yes</span>' : '<span class="badge bg-secondary bg-opacity-10 text-secondary">No</span>' !!}</td>
                        <td>{{ $c->sort_order ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No salary components found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<x-pagination :paginator="$components" />
@endsection
