@extends('layouts.app')
@section('title', 'Leave Encashments')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-money-bill-wave me-2"></i>Leave Encashments</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">HR</li><li class="breadcrumb-item">Employee Leave Management</li><li class="breadcrumb-item active">Leave Encashments</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Employee</label>
                <select name="employee_id" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach($employees as $e)
                    <option value="{{ $e->id }}" {{ request('employee_id') == $e->id ? 'selected' : '' }}>{{ $e->first_name }} {{ $e->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Remarks..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
            @if(request()->anyFilled(['status','employee_id','search']))
            <div class="col-12">
                <a href="{{ route('hr.employee-leave.encashments') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear Filters</a>
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
                    <tr><th>#</th><th>Employee</th><th>Leave Type</th><th>Days</th><th>Rate/Day</th><th>Total</th><th>Date</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($encashments as $e)
                    <tr>
                        <td>{{ $e->id }}</td>
                        <td class="fw-semibold">{{ $e->employee->full_name ?? 'N/A' }}</td>
                        <td>{{ $e->leaveType->name ?? 'N/A' }}</td>
                        <td>{{ $e->days_encashed }}</td>
                        <td>${{ number_format($e->amount_per_day, 2) ?? '-' }}</td>
                        <td class="fw-bold">${{ number_format($e->total_amount, 2) ?? '-' }}</td>
                        <td>{{ $e->encashment_date }}</td>
                        <td>
                            @php
                                $sc = ['pending' => 'warning', 'approved' => 'info', 'paid' => 'success', 'rejected' => 'danger'];
                                $s = $sc[$e->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $s }} bg-opacity-10 text-{{ $s }}">{{ ucfirst($e->status) }}</span>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="#" class="btn btn-sm btn-outline-info" title="View"><i class="fas fa-eye"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">No encashments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<x-pagination :paginator="$encashments" />
@endsection
