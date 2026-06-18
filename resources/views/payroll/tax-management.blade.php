@extends('layouts.app')
@section('title', 'Tax Management')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-file-invoice-dollar me-2"></i>Tax Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Payroll</li><li class="breadcrumb-item active">Tax Management</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Type</label>
                <select name="type" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="percentage" {{ request('type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                    <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                    <option value="slab" {{ request('type') == 'slab' ? 'selected' : '' }}>Slab</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Tax name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
            @if(request()->anyFilled(['type','search']))
            <div class="col-12"><a href="{{ route('payroll.tax-management') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear</a></div>
            @endif
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Name</th><th>Code</th><th>Type</th><th>Rate</th><th>Min Amount</th><th>Max Amount</th><th>Effective</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($taxes as $t)
                    <tr>
                        <td>{{ $t->id }}</td>
                        <td class="fw-semibold">{{ $t->name }}</td>
                        <td><span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $t->code ?? '-' }}</span></td>
                        <td><span class="badge bg-info bg-opacity-10 text-info">{{ ucfirst($t->type) }}</span></td>
                        <td>{{ $t->rate }}%</td>
                        <td>{{ $t->min_amount ? '$'.number_format($t->min_amount, 2) : '-' }}</td>
                        <td>{{ $t->max_amount ? '$'.number_format($t->max_amount, 2) : '-' }}</td>
                        <td>
                            @if($t->effective_from)
                                {{ $t->effective_from->format('M d, Y') }}
                                @if($t->effective_to) - {{ $t->effective_to->format('M d, Y') }}@endif
                            @else
                                -
                            @endif
                        </td>
                        <td>{!! $t->is_active ? '<span class="badge bg-success bg-opacity-10 text-success">Active</span>' : '<span class="badge bg-secondary bg-opacity-10 text-secondary">Inactive</span>' !!}</td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">No tax rules found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<x-pagination :paginator="$taxes" />
@endsection
