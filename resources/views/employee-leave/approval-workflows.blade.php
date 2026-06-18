@extends('layouts.app')
@section('title', 'Approval Workflows')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-check-double me-2"></i>Approval Workflows</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">HR</li><li class="breadcrumb-item">Employee Leave Management</li><li class="breadcrumb-item active">Approval Workflows</li></ol></nav>
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
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Workflow name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
            @if(request()->anyFilled(['leave_type_id','search']))
            <div class="col-12">
                <a href="{{ route('hr.employee-leave.approval-workflows') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear Filters</a>
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
                    <tr><th>#</th><th>Name</th><th>Leave Type</th><th>Level</th><th>Approver Type</th><th>Condition</th><th>Active</th></tr>
                </thead>
                <tbody>
                    @forelse($workflows as $w)
                    <tr>
                        <td>{{ $w->id }}</td>
                        <td class="fw-semibold">{{ $w->name }}</td>
                        <td>{{ $w->leaveType->name ?? 'All Types' }}</td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary">Level {{ $w->approval_level }}</span></td>
                        <td>{{ ucfirst($w->approver_type ?? 'N/A') }}</td>
                        <td>
                            @if($w->condition_field)
                                <small>{{ $w->condition_field }} {{ $w->condition_operator }} {{ $w->condition_value }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{!! $w->is_active ? '<span class="badge bg-success bg-opacity-10 text-success">Active</span>' : '<span class="badge bg-secondary bg-opacity-10 text-secondary">Inactive</span>' !!}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No approval workflows found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<x-pagination :paginator="$workflows" />
@endsection
