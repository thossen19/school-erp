@extends('layouts.app')
@section('title', 'Recruitment')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-user-plus me-2"></i>Recruitment</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">HR</a></li><li class="breadcrumb-item active">Recruitment</li></ol></nav>
    </div>
    <a href="{{ route('hr.recruitment.create') }}" class="btn btn-primary btn-sm">+ New Job Posting</a>
</div>

<div class="filter-bar">
    <form method="GET" action="{{ route('hr.recruitment') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Status</label>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Job title..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Job Title','Department','Designation','Vacancies','Salary Range','Posted Date','Closing Date','Status','Actions']">
            @forelse($postings as $p)
            <tr>
                <td><a href="{{ route('hr.recruitment.show', $p->id) }}" class="text-decoration-none fw-semibold">{{ $p->job_title }}</a></td>
                <td>{{ $p->department_name ?? '-' }}</td>
                <td>{{ $p->designation_name ?? '-' }}</td>
                <td>{{ $p->vacancies }}</td>
                <td>{{ $p->salary_range ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($p->posted_date)->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($p->closing_date)->format('d-m-Y') }}</td>
                <td>
                    @php
                        $badge = match($p->status) { 'open' => 'success', 'closed' => 'danger', 'draft' => 'warning', default => 'secondary' };
                    @endphp
                    <span class="badge bg-{{ $badge }}">{{ ucfirst($p->status) }}</span>
                </td>
                <td>
                    <div class="table-actions">
                        <a href="{{ route('hr.recruitment.show', $p->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('hr.recruitment.edit', $p->id) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('hr.recruitment.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this posting?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center text-muted py-3">No job postings found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$postings" />
</div>
@endsection
