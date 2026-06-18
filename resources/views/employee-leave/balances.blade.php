@extends('layouts.app')
@section('title', 'Leave Balances')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-balance-scale me-2"></i>Leave Balances</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">HR</li><li class="breadcrumb-item">Employee Leave Management</li><li class="breadcrumb-item active">Leave Balances</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Leave Type</label>
                <select name="leave_type_id" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    @foreach($leaveTypes as $lt)
                    <option value="{{ $lt->id }}" {{ request('leave_type_id') == $lt->id ? 'selected' : '' }}>{{ $lt->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">User</label>
                <select name="user_id" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="User name/email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
            @if(request()->anyFilled(['leave_type_id','user_id','search']))
            <div class="col-12">
                <a href="{{ route('hr.employee-leave.balances') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear Filters</a>
            </div>
            @endif
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>User</th><th>Leave Type</th><th>Total Days</th><th>Used</th><th>Remaining</th><th>Carried Forward</th></tr>
                </thead>
                <tbody>
                    @forelse($balances as $b)
                    <tr>
                        <td>{{ $b->id }}</td>
                        <td class="fw-semibold">{{ $b->user->name ?? 'N/A' }}<br><small class="text-muted">{{ $b->user->email ?? '' }}</small></td>
                        <td><span class="badge bg-info bg-opacity-10 text-info">{{ $b->leaveType->name ?? 'N/A' }}</span></td>
                        <td>{{ $b->total_days }}</td>
                        <td>{{ $b->used_days }}</td>
                        <td>
                            @php
                                $balClass = $b->remaining_days <= 2 ? 'danger' : ($b->remaining_days <= 5 ? 'warning' : 'success');
                            @endphp
                            <span class="badge bg-{{ $balClass }} bg-opacity-10 text-{{ $balClass }} fs-6">{{ $b->remaining_days }}</span>
                        </td>
                        <td>{{ $b->carried_forward ?? 0 }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No leave balances found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<x-pagination :paginator="$balances" />
@endsection
