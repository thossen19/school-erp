@extends('layouts.app')
@section('title', 'Holiday Calendar')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-calendar-alt me-2"></i>Holiday Calendar</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="{{ route_if_exists('dashboard') }}"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">HR</li><li class="breadcrumb-item">Employee Leave</li><li class="breadcrumb-item active">Holiday Calendar</li></ol></nav>
    </div>
    <div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#holidayModal"><i class="fas fa-plus me-1"></i>Add Holiday</button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Year</label>
                <select name="year" class="form-select form-select-sm">
                    <option value="">All Years</option>
                    @foreach($years as $y)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Type</label>
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    <option value="public" {{ request('type') == 'public' ? 'selected' : '' }}>Public</option>
                    <option value="religious" {{ request('type') == 'religious' ? 'selected' : '' }}>Religious</option>
                    <option value="school" {{ request('type') == 'school' ? 'selected' : '' }}>School</option>
                    <option value="exam" {{ request('type') == 'exam' ? 'selected' : '' }}>Exam</option>
                    <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Holiday name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
            @if(request()->anyFilled(['year','type','search']))
            <div class="col-12">
                <a href="{{ route('hr.employee-leave.holiday-calendar') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear Filters</a>
            </div>
            @endif
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Type</th>
                        <th>Recurring</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($holidays as $h)
                    <tr>
                        <td>{{ $h->id }}</td>
                        <td class="fw-semibold">{{ $h->name }}</td>
                        <td>{{ $h->date->format('M d, Y') }}</td>
                        <td>{{ $h->date->format('l') }}</td>
                        <td>
                            @php
                                $typeStyles = ['public'=>'primary','religious'=>'warning','school'=>'info','exam'=>'danger','other'=>'secondary'];
                                $style = $typeStyles[$h->type] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $style }} bg-opacity-10 text-{{ $style }}">{{ ucfirst($h->type) }}</span>
                        </td>
                        <td>{!! $h->is_recurring_annually ? '<span class="badge bg-success bg-opacity-10 text-success">Yes</span>' : '<span class="badge bg-secondary bg-opacity-10 text-secondary">No</span>' !!}</td>
                        <td>{!! $h->status ? '<span class="badge bg-success bg-opacity-10 text-success">Active</span>' : '<span class="badge bg-secondary bg-opacity-10 text-secondary">Inactive</span>' !!}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#editHolidayModal{{ $h->id }}" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteHolidayModal{{ $h->id }}" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">No holidays found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<x-pagination :paginator="$holidays" />

{{-- Create Modal --}}
<div class="modal fade" id="holidayModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('hr.employee-leave.holiday-calendar.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add Holiday</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Holiday Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="">Select</option>
                                <option value="public">Public</option>
                                <option value="religious">Religious</option>
                                <option value="school">School</option>
                                <option value="exam">Exam</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Description</label>
                        <textarea name="description" rows="2" class="form-control"></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input type="hidden" name="is_recurring_annually" value="0">
                                <input type="checkbox" name="is_recurring_annually" value="1" class="form-check-input" id="createRecurring">
                                <label class="form-check-label fw-medium" for="createRecurring">Recurring Annually</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" name="status" value="1" class="form-check-input" id="createStatus" checked>
                                <label class="form-check-label fw-medium" for="createStatus">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Holiday</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit & Delete Modals --}}
@foreach($holidays as $h)
<div class="modal fade" id="editHolidayModal{{ $h->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('hr.employee-leave.holiday-calendar.update', $h->id) }}">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Holiday</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Holiday Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ $h->name }}" class="form-control" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" value="{{ $h->date->format('Y-m-d') }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="">Select</option>
                                <option value="public" {{ $h->type == 'public' ? 'selected' : '' }}>Public</option>
                                <option value="religious" {{ $h->type == 'religious' ? 'selected' : '' }}>Religious</option>
                                <option value="school" {{ $h->type == 'school' ? 'selected' : '' }}>School</option>
                                <option value="exam" {{ $h->type == 'exam' ? 'selected' : '' }}>Exam</option>
                                <option value="other" {{ $h->type == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Description</label>
                        <textarea name="description" rows="2" class="form-control">{{ $h->description }}</textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input type="hidden" name="is_recurring_annually" value="0">
                                <input type="checkbox" name="is_recurring_annually" value="1" class="form-check-input" id="editRecurring{{ $h->id }}" {{ $h->is_recurring_annually ? 'checked' : '' }}>
                                <label class="form-check-label fw-medium" for="editRecurring{{ $h->id }}">Recurring Annually</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" name="status" value="1" class="form-check-input" id="editStatus{{ $h->id }}" {{ $h->status ? 'checked' : '' }}>
                                <label class="form-check-label fw-medium" for="editStatus{{ $h->id }}">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update Holiday</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteHolidayModal{{ $h->id }}" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form method="POST" action="{{ route('hr.employee-leave.holiday-calendar.destroy', $h->id) }}">
                @csrf @method('DELETE')
                <div class="modal-body text-center py-4">
                    <i class="fas fa-exclamation-triangle text-danger fs-1 mb-3 d-block"></i>
                    <h6 class="fw-bold">Delete Holiday?</h6>
                    <p class="text-muted small mb-0">Are you sure you want to delete <strong>{{ $h->name }}</strong>? This cannot be undone.</p>
                </div>
                <div class="modal-footer justify-content-center border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash me-1"></i>Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection