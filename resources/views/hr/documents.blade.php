@extends('layouts.app')
@section('title', 'Employee Documents')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-file-alt me-2"></i>Employee Documents</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">HR</li><li class="breadcrumb-item active">Employee Documents</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Document Type</label>
                <select name="document_type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    @foreach($documentTypes as $t)
                    <option value="{{ $t }}" {{ request('document_type') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
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
                <label class="form-label fw-semibold small">Verified</label>
                <select name="verified" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="yes" {{ request('verified') == 'yes' ? 'selected' : '' }}>Verified</option>
                    <option value="no" {{ request('verified') == 'no' ? 'selected' : '' }}>Unverified</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Document name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
            @if(request()->anyFilled(['document_type','employee_id','verified','search']))
            <div class="col-12">
                <a href="{{ route('hr.documents') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear Filters</a>
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
                        <th>Document Type</th>
                        <th>Document Number</th>
                        <th>Issue Date</th>
                        <th>Expiry Date</th>
                        <th>Verified</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $d)
                    <tr>
                        <td>{{ $d->id }}</td>
                        <td class="fw-semibold">{{ $d->employee->full_name ?? 'N/A' }}<br><small class="text-muted">{{ $d->employee->employee_no ?? '' }}</small></td>
                        <td><span class="badge bg-info bg-opacity-10 text-info">{{ $d->document_type }}</span></td>
                        <td>{{ $d->document_number ?? '-' }}</td>
                        <td>{{ $d->issue_date ?? '-' }}</td>
                        <td>
                            @if($d->expiry_date)
                                <span class="badge bg-{{ $d->expiry_date < now() ? 'danger' : ($d->expiry_date <= now()->addMonth() ? 'warning' : 'success') }} bg-opacity-10 text-{{ $d->expiry_date < now() ? 'danger' : ($d->expiry_date <= now()->addMonth() ? 'warning' : 'success') }}">
                                    {{ $d->expiry_date }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($d->verified)
                                <span class="badge bg-success bg-opacity-10 text-success"><i class="fas fa-check-circle me-1"></i>Verified</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">Pending</span>
                            @endif
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="#" class="btn btn-sm btn-outline-info" title="View"><i class="fas fa-eye"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">No employee documents found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<x-pagination :paginator="$documents" />
@endsection
