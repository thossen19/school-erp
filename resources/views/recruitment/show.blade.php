@extends('layouts.app')
@section('title', 'Job Posting')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-briefcase me-2"></i>{{ $posting->job_title }}</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('hr.recruitment') }}">Recruitment</a></li><li class="breadcrumb-item active">{{ $posting->job_title }}</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('hr.recruitment.edit', $posting->id) }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i>Edit</a>
        <form action="{{ route('hr.recruitment.destroy', $posting->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this posting?')">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger"><i class="fas fa-trash me-1"></i>Delete</button>
        </form>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="fw-semibold border-bottom pb-2 mb-3">Posting Details</h6>
                <div class="mb-2"><small class="text-muted d-block">Department</small><span class="fw-semibold">{{ $posting->department_name ?? '-' }}</span></div>
                <div class="mb-2"><small class="text-muted d-block">Designation</small><span class="fw-semibold">{{ $posting->designation_name ?? '-' }}</span></div>
                <div class="mb-2"><small class="text-muted d-block">Vacancies</small><span class="fw-bold fs-5 text-primary">{{ $posting->vacancies }}</span></div>
                <div class="mb-2"><small class="text-muted d-block">Salary Range</small><span class="fw-semibold">{{ $posting->salary_range ?? 'Not specified' }}</span></div>
                <div class="mb-2"><small class="text-muted d-block">Posted Date</small><span>{{ \Carbon\Carbon::parse($posting->posted_date)->format('d-m-Y') }}</span></div>
                <div class="mb-2"><small class="text-muted d-block">Closing Date</small><span>{{ \Carbon\Carbon::parse($posting->closing_date)->format('d-m-Y') }}</span></div>
                <div class="mb-2">
                    <small class="text-muted d-block">Status</small>
                    @php $badge = match($posting->status) { 'open' => 'success', 'closed' => 'danger', 'draft' => 'warning', default => 'secondary' }; @endphp
                    <span class="badge bg-{{ $badge }} fs-6">{{ ucfirst($posting->status) }}</span>
                </div>
                @if($posting->description)
                <div class="mb-2"><small class="text-muted d-block">Description</small><p class="mb-0">{{ $posting->description }}</p></div>
                @endif
                @if($posting->requirements)
                <div class="mb-2"><small class="text-muted d-block">Requirements</small><p class="mb-0">{{ $posting->requirements }}</p></div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-semibold mb-0"><i class="fas fa-users me-2"></i>Applications</h6>
            </div>
            <div class="card-body p-0">
                <x-table :headers="['#','Applicant','Email','Phone','Applied Date','Status']">
                    @forelse($applications as $a)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $a->applicant_name }}</td>
                        <td>{{ $a->email }}</td>
                        <td>{{ $a->phone ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($a->application_date ?? $a->created_at)->format('d-m-Y') }}</td>
                        <td>
                            @php
                                $abg = match($a->status) { 'pending' => 'warning', 'shortlisted' => 'info', 'interviewed' => 'primary', 'offered' => 'success', 'rejected' => 'danger', 'hired' => 'dark', default => 'secondary' };
                            @endphp
                            <span class="badge bg-{{ $abg }}">{{ ucfirst($a->status ?? 'pending') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-3">No applications yet.</td></tr>
                    @endforelse
                </x-table>
            </div>
            <x-pagination :paginator="$applications" />
        </div>
    </div>
</div>
@endsection
