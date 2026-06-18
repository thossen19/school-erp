@extends('layouts.app')
@section('title', 'Leave Types')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-tags me-2"></i>Leave Types</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">HR</li><li class="breadcrumb-item">Employee Leave Management</li><li class="breadcrumb-item active">Leave Types</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Name or code..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
            @if(request('search'))
            <div class="col-12">
                <a href="{{ route('hr.employee-leave.types') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear</a>
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
                    <tr><th>#</th><th>Name</th><th>Code</th><th>Max Days/Year</th><th>Max Consecutive</th><th>Paid</th><th>Recurring</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($types as $t)
                    <tr>
                        <td>{{ $t->id }}</td>
                        <td class="fw-semibold">{{ $t->name }}</td>
                        <td><span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $t->code }}</span></td>
                        <td>{{ $t->max_days_per_year }}</td>
                        <td>{{ $t->max_consecutive_days ?? 'N/A' }}</td>
                        <td>{!! $t->is_paid ? '<span class="badge bg-success bg-opacity-10 text-success">Yes</span>' : '<span class="badge bg-danger bg-opacity-10 text-danger">No</span>' !!}</td>
                        <td>{!! $t->is_recurring ? '<span class="badge bg-info bg-opacity-10 text-info">Yes</span>' : '<span class="badge bg-secondary bg-opacity-10 text-secondary">No</span>' !!}</td>
                        <td>{!! $t->status ? '<span class="badge bg-success bg-opacity-10 text-success">Active</span>' : '<span class="badge bg-secondary bg-opacity-10 text-secondary">Inactive</span>' !!}</td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">No leave types found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<x-pagination :paginator="$types" />
@endsection
