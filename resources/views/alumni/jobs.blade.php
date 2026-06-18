@extends('layouts.app')
@section('title', 'Job Board')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-briefcase me-2"></i>Job Board</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Alumni</li><li class="breadcrumb-item active">Job Board</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>Post Job</button>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label fw-semibold small">Search</label><input type="text" name="search" class="form-control form-control-sm" placeholder="Title, company or location..." value="{{ request('search') }}"></div>
            <div class="col-md-2"><label class="form-label fw-semibold small">Type</label><select name="job_type" class="form-select form-select-sm"><option value="">All</option><option value="full_time" {{ request('job_type') === 'full_time' ? 'selected' : '' }}>Full Time</option><option value="part_time" {{ request('job_type') === 'part_time' ? 'selected' : '' }}>Part Time</option><option value="contract" {{ request('job_type') === 'contract' ? 'selected' : '' }}>Contract</option><option value="internship" {{ request('job_type') === 'internship' ? 'selected' : '' }}>Internship</option></select></div>
            <div class="col-md-2"><label class="form-label fw-semibold small">Status</label><select name="status" class="form-select form-select-sm"><option value="">All</option><option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option><option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option></select></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>@if(request('search')||request('job_type')||request('status'))<a href="{{ route('alumni.jobs') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-times"></i></a>@endif</div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Title','Company','Location','Type','Salary','Deadline','Status','Actions']">
            @forelse($jobs as $j)
            <tr>
                <td>{{ $j->id }}</td>
                <td class="fw-semibold">{{ $j->title }}</td>
                <td>{{ $j->company }}</td>
                <td>{{ $j->location ?? '-' }}</td>
                <td><span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $j->job_type)) }}</span></td>
                <td>{{ $j->salary_range ?? '-' }}</td>
                <td>{{ $j->application_deadline ?? '-' }}</td>
                <td>@if($j->status === 'active')<span class="badge bg-success">Active</span>@else<span class="badge bg-secondary">Closed</span>@endif</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" title="Edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $j->id }}"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('alumni.jobs.delete', $j->id) }}" class="d-inline" onsubmit="return confirm('Delete this job?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center py-4 text-muted">No jobs posted.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$jobs" />

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <form method="POST" action="{{ route('alumni.jobs.store') }}">@csrf
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-1"></i>Post Job</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Job Title</label><input type="text" name="title" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Company</label><input type="text" name="company" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Location</label><input type="text" name="location" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Job Type</label><select name="job_type" class="form-select"><option value="full_time">Full Time</option><option value="part_time">Part Time</option><option value="contract">Contract</option><option value="internship">Internship</option></select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Salary Range</label><input type="text" name="salary_range" class="form-control" placeholder="e.g. $50k-$80k"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Application Deadline</label><input type="date" name="application_deadline" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Contact Email</label><input type="email" name="contact_email" class="form-control"></div>
                    <div class="col-12"><label class="form-label fw-semibold">Description</label><textarea name="description" class="form-control" rows="4"></textarea></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Post</button>
            </div>
        </form>
    </div></div>
</div>

@foreach($jobs as $j)
<div class="modal fade" id="editModal{{ $j->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <form method="POST" action="{{ route('alumni.jobs.update', $j->id) }}">@csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-edit me-1"></i>Edit Job</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Job Title</label><input type="text" name="title" class="form-control" value="{{ $j->title }}" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Company</label><input type="text" name="company" class="form-control" value="{{ $j->company }}" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Location</label><input type="text" name="location" class="form-control" value="{{ $j->location }}"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Job Type</label><select name="job_type" class="form-select"><option value="full_time" {{ $j->job_type === 'full_time' ? 'selected' : '' }}>Full Time</option><option value="part_time" {{ $j->job_type === 'part_time' ? 'selected' : '' }}>Part Time</option><option value="contract" {{ $j->job_type === 'contract' ? 'selected' : '' }}>Contract</option><option value="internship" {{ $j->job_type === 'internship' ? 'selected' : '' }}>Internship</option></select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Salary Range</label><input type="text" name="salary_range" class="form-control" value="{{ $j->salary_range }}"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Application Deadline</label><input type="date" name="application_deadline" class="form-control" value="{{ $j->application_deadline }}"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Contact Email</label><input type="email" name="contact_email" class="form-control" value="{{ $j->contact_email }}"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Status</label><select name="status" class="form-select"><option value="active" {{ $j->status === 'active' ? 'selected' : '' }}>Active</option><option value="closed" {{ $j->status === 'closed' ? 'selected' : '' }}>Closed</option></select></div>
                    <div class="col-12"><label class="form-label fw-semibold">Description</label><textarea name="description" class="form-control" rows="4">{{ $j->description }}</textarea></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
            </div>
        </form>
    </div></div>
</div>
@endforeach
@endsection