@extends('layouts.app')
@section('title', $type)
@section('content')
@php
    $icons = [
        'Transfer Certificate' => 'fa-file-export',
        'Character Certificate' => 'fa-user-check',
        'Bonafide Certificate' => 'fa-id-card',
        'Course Completion Certificate' => 'fa-graduation-cap',
    ];
    $icon = $icons[$type] ?? 'fa-certificate';
    $route = match($type) {
        'Transfer Certificate' => 'certificates.transfer',
        'Character Certificate' => 'certificates.character',
        'Bonafide Certificate' => 'certificates.bonafide',
        'Course Completion Certificate' => 'certificates.course-completion',
        default => 'certificates.custom',
    };
@endphp
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas {{ $icon }} me-2"></i>{{ $type }}</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Certificates</li><li class="breadcrumb-item active">{{ $type }}</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>Issue New</button>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Certificate # or student name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="revoked" {{ request('status') === 'revoked' ? 'selected' : '' }}>Revoked</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>
                @if(request('search') || request('status'))<a href="{{ route($route) }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-times"></i></a>@endif
            </div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Certificate No','Student','Issue Date','Template','QR','Signature','Status','Actions']">
            @forelse($certificates as $c)
            <tr>
                <td>{{ $c->id }}</td>
                <td class="fw-semibold font-monospace">{{ $c->certificate_number }}</td>
                <td>{{ $c->first_name }} {{ $c->last_name }}<br><small class="text-muted">{{ $c->admission_no ?? '' }}</small></td>
                <td>{{ \Carbon\Carbon::parse($c->issue_date)->format('d-m-Y') }}</td>
                <td>{{ $c->template_name ?? '-' }}</td>
                <td>@if($c->qr_code)<span class="badge bg-success">Yes</span>@else<span class="badge bg-secondary">No</span>@endif</td>
                <td>@if($c->digital_signature)<span class="badge bg-success">Signed</span>@else<span class="badge bg-secondary">Unsigned</span>@endif</td>
                <td>
                    @php $badge = ['active' => 'success', 'draft' => 'warning', 'revoked' => 'danger']; @endphp
                    <span class="badge bg-{{ $badge[$c->status] ?? 'secondary' }}">{{ ucfirst($c->status) }}</span>
                </td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" title="Edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $c->id }}"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('certificates.destroy', $c->id) }}" class="d-inline" onsubmit="return confirm('Delete this certificate?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center py-4 text-muted">No {{ $type }} records found.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$certificates" />

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <form method="POST" action="{{ route('certificates.store') }}">@csrf
            <input type="hidden" name="certificate_type" value="{{ $type }}">
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-1"></i>Issue {{ $type }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Student</label><select name="student_id" class="form-select" required><option value="">Select</option>@foreach($students as $s)<option value="{{ $s->id }}">{{ $s->first_name }} {{ $s->last_name }} ({{ $s->admission_no }})</option>@endforeach</select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Template</label><select name="template_id" class="form-select"><option value="">Select (optional)</option>@foreach($templates as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach</select></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Issue Date</label><input type="date" name="issue_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Status</label><select name="status" class="form-select"><option value="active">Active</option><option value="draft">Draft</option></select></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Issue Certificate</button>
            </div>
        </form>
    </div></div>
</div>

@foreach($certificates as $c)
<div class="modal fade" id="editModal{{ $c->id }}" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('certificates.update', $c->id) }}">@csrf @method('PUT')
            <input type="hidden" name="certificate_type" value="{{ $type }}">
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-edit me-1"></i>Edit {{ $type }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label fw-semibold">Certificate No</label><p class="form-control-plaintext font-monospace">{{ $c->certificate_number }}</p></div>
                <div class="mb-3"><label class="form-label fw-semibold">Student</label><p class="form-control-plaintext">{{ $c->first_name }} {{ $c->last_name }}</p></div>
                <div class="mb-3"><label class="form-label fw-semibold">Issue Date</label><input type="date" name="issue_date" class="form-control" value="{{ $c->issue_date }}"></div>
                <div class="mb-3"><label class="form-label fw-semibold">Status</label><select name="status" class="form-select"><option value="active" {{ $c->status === 'active' ? 'selected' : '' }}>Active</option><option value="draft" {{ $c->status === 'draft' ? 'selected' : '' }}>Draft</option><option value="revoked" {{ $c->status === 'revoked' ? 'selected' : '' }}>Revoked</option></select></div>
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
