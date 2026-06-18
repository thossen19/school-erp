@extends('layouts.app')
@section('title', 'Leave Policies')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-file-contract me-2"></i>Leave Policies</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">HR</li><li class="breadcrumb-item">Employee Leave Management</li><li class="breadcrumb-item active">Leave Policies</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Policy name or code..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
            @if(request('search'))
            <div class="col-12">
                <a href="{{ route('hr.employee-leave.policies') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear</a>
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
                    <tr><th>#</th><th>Name</th><th>Code</th><th>Max Days/Year</th><th>Max Consecutive</th><th>Paid</th><th>Approval Req.</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($policies as $p)
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td class="fw-semibold">{{ $p->name }}</td>
                        <td><span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $p->code ?? '-' }}</span></td>
                        <td>{{ $p->max_days_per_year }}</td>
                        <td>{{ $p->max_consecutive_days ?? 'N/A' }}</td>
                        <td>{!! $p->is_paid ? '<span class="badge bg-success bg-opacity-10 text-success">Yes</span>' : '<span class="badge bg-danger bg-opacity-10 text-danger">No</span>' !!}</td>
                        <td>{!! $p->requires_approval ? '<span class="badge bg-warning bg-opacity-10 text-warning">Yes</span>' : '<span class="badge bg-info bg-opacity-10 text-info">No</span>' !!}</td>
                        <td>{!! $p->is_active ? '<span class="badge bg-success bg-opacity-10 text-success">Active</span>' : '<span class="badge bg-secondary bg-opacity-10 text-secondary">Inactive</span>' !!}</td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">No leave policies found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<x-pagination :paginator="$policies" />
@endsection
