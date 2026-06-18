@extends('layouts.app')
@section('title', 'Employee Promotions')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-arrow-up me-2"></i>Employee Promotions</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">HR</li><li class="breadcrumb-item active">Employee Promotions</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Employee</label>
                <select name="employee_id" class="form-select form-select-sm">
                    <option value="">All Employees</option>
                    @foreach($employees as $e)
                    <option value="{{ $e->id }}" {{ request('employee_id') == $e->id ? 'selected' : '' }}>{{ $e->first_name }} {{ $e->last_name }} ({{ $e->employee_no }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search reason..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
            @if(request()->anyFilled(['employee_id','status','search']))
            <div class="col-12">
                <a href="{{ route('hr.promotions') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear Filters</a>
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
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>From Designation</th>
                        <th>To Designation</th>
                        <th>Previous Salary</th>
                        <th>New Salary</th>
                        <th>Promotion Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promotions as $p)
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td class="fw-semibold">{{ $p->employee->full_name ?? 'N/A' }}<br><small class="text-muted">{{ $p->employee->employee_no ?? '' }}</small></td>
                        <td>{{ $p->fromDesignation->name ?? '-' }}</td>
                        <td><span class="badge bg-success bg-opacity-10 text-success fw-normal">{{ $p->toDesignation->name ?? '-' }}</span></td>
                        <td>${{ number_format($p->previous_salary, 2) ?? '-' }}</td>
                        <td>${{ number_format($p->new_salary, 2) ?? '-' }}</td>
                        <td>{{ $p->promotion_date }}</td>
                        <td>
                            @php
                                $sc = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'];
                                $s = $sc[$p->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $s }} bg-opacity-10 text-{{ $s }}">{{ ucfirst($p->status) }}</span>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="#" class="btn btn-sm btn-outline-info" title="View"><i class="fas fa-eye"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">No promotions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<x-pagination :paginator="$promotions" />
@endsection
