@extends('layouts.app')
@section('title', 'Bonus Management')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-gift me-2"></i>Bonus Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Payroll</li><li class="breadcrumb-item active">Bonus Management</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Bonus Type</label>
                <select name="bonus_type" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach($types as $bt)
                    <option value="{{ $bt }}" {{ request('bonus_type') == $bt ? 'selected' : '' }}>{{ ucfirst($bt) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Employee name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
            @if(request()->anyFilled(['bonus_type','status','search']))
            <div class="col-12"><a href="{{ route('payroll.bonus-management') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear</a></div>
            @endif
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Employee</th><th>Type</th><th>Amount</th><th>Date</th><th>Taxable</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($bonuses as $b)
                    <tr>
                        <td>{{ $b->id }}</td>
                        <td class="fw-semibold">{{ $b->employee->full_name ?? 'N/A' }}<br><small class="text-muted">{{ $b->employee->employee_no ?? '' }}</small></td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ ucfirst($b->bonus_type) }}</span></td>
                        <td class="fw-bold">${{ number_format($b->amount, 2) }}</td>
                        <td>{{ $b->bonus_date }}</td>
                        <td>{!! $b->is_taxable ? '<span class="badge bg-warning bg-opacity-10 text-warning">Taxable</span>' : '<span class="badge bg-success bg-opacity-10 text-success">Non-taxable</span>' !!}</td>
                        <td>
                            @php $sc = ['pending' => 'warning', 'approved' => 'info', 'paid' => 'success']; @endphp
                            <span class="badge bg-{{ $sc[$b->status] ?? 'secondary' }} bg-opacity-10 text-{{ $sc[$b->status] ?? 'secondary' }}">{{ ucfirst($b->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No bonuses found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<x-pagination :paginator="$bonuses" />
@endsection
