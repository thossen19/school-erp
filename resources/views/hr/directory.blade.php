@extends('layouts.app')
@section('title', 'Staff Directory')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-address-book me-2"></i>Staff Directory</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">HR</li><li class="breadcrumb-item active">Staff Directory</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Department</label>
                <select name="department_id" class="form-select form-select-sm">
                    <option value="">All Departments</option>
                    @foreach($departments as $d)
                    <option value="{{ $d->id }}" {{ request('department_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Designation</label>
                <select name="designation_id" class="form-select form-select-sm">
                    <option value="">All Designations</option>
                    @foreach($designations as $d)
                    <option value="{{ $d->id }}" {{ request('designation_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Employment Type</label>
                <select name="employment_type" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="permanent" {{ request('employment_type') == 'permanent' ? 'selected' : '' }}>Permanent</option>
                    <option value="contract" {{ request('employment_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                    <option value="temporary" {{ request('employment_type') == 'temporary' ? 'selected' : '' }}>Temporary</option>
                    <option value="probation" {{ request('employment_type') == 'probation' ? 'selected' : '' }}>Probation</option>
                    <option value="intern" {{ request('employment_type') == 'intern' ? 'selected' : '' }}>Intern</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Name, email, phone, employee no..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
            @if(request()->anyFilled(['department_id','designation_id','employment_type','search']))
            <div class="col-12">
                <a href="{{ route('hr.directory') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear Filters</a>
            </div>
            @endif
        </form>
    </div>
</div>
<div class="row g-3">
    @forelse($employees as $emp)
    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="mx-auto rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:80px;height:80px;">
                        @if($emp->photo)
                        <img src="{{ asset('storage/'.$emp->photo) }}" class="rounded-circle w-100 h-100" style="object-fit:cover;">
                        @else
                        <span class="fs-2 fw-bold text-primary">{{ substr($emp->first_name, 0, 1) }}{{ substr($emp->last_name, 0, 1) }}</span>
                        @endif
                    </div>
                </div>
                <h6 class="fw-semibold mb-1">{{ $emp->full_name }}</h6>
                <p class="text-muted small mb-2">{{ $emp->designation->name ?? 'N/A' }}</p>
                <div class="d-flex justify-content-center gap-2 mb-2">
                    <span class="badge bg-info bg-opacity-10 text-info">{{ $emp->department->name ?? 'N/A' }}</span>
                    @if($emp->employment_type)
                    <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ ucfirst($emp->employment_type) }}</span>
                    @endif
                </div>
                <hr class="my-2">
                <div class="text-start small text-muted">
                    <div><i class="fas fa-id-badge me-1" style="width:16px;"></i>{{ $emp->employee_no ?? 'N/A' }}</div>
                    @if($emp->email)
                    <div><i class="fas fa-envelope me-1" style="width:16px;"></i>{{ $emp->email }}</div>
                    @endif
                    @if($emp->phone)
                    <div><i class="fas fa-phone me-1" style="width:16px;"></i>{{ $emp->phone }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-5 text-muted">
                <i class="fas fa-users fa-3x mb-3"></i>
                <p class="mb-0">No employees found matching your criteria.</p>
            </div>
        </div>
    </div>
    @endforelse
</div>
<x-pagination :paginator="$employees" />
@endsection
