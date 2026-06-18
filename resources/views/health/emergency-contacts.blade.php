@extends('layouts.app')
@section('title', 'Emergency Contacts')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-phone-alt me-2"></i>Emergency Contacts</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Health Records</li><li class="breadcrumb-item active">Emergency Contacts</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>Add Contact</button>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Student or contact name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>
                @if(request('search'))<a href="{{ route('health.emergency-contacts') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-times"></i></a>@endif
            </div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Student','Contact Name','Relation','Phone','Email','Primary','Actions']">
            @forelse($records as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td class="fw-semibold">{{ $r->first_name }} {{ $r->last_name }}</td>
                <td>{{ $r->contact_name }}</td>
                <td>{{ $r->relation }}</td>
                <td>{{ $r->phone }}</td>
                <td>{{ $r->email ?? '-' }}</td>
                <td>@if($r->is_primary)<span class="badge bg-success">Primary</span>@else<span class="badge bg-secondary">No</span>@endif</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" title="Edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $r->id }}"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('health.emergency-contacts.delete', $r->id) }}" class="d-inline" onsubmit="return confirm('Delete this contact?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-4 text-muted">No emergency contacts found.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$records" />

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('health.emergency-contacts.store') }}">@csrf
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-1"></i>Add Emergency Contact</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label fw-semibold">Student</label><select name="student_id" class="form-select" required><option value="">Select</option>@foreach(\App\Models\Student\Student::where('school_id', session('school_id', 1))->get() as $s)<option value="{{ $s->id }}">{{ $s->first_name }} {{ $s->last_name }} ({{ $s->admission_no }})</option>@endforeach</select></div>
                <div class="mb-3"><label class="form-label fw-semibold">Contact Name</label><input type="text" name="contact_name" class="form-control" required></div>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Relation</label><select name="relation" class="form-select" required><option value="">Select</option><option>Father</option><option>Mother</option><option>Guardian</option><option>Sibling</option><option>Relative</option><option>Other</option></select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Phone</label><input type="text" name="phone" class="form-control" required></div>
                </div>
                <div class="mb-3"><label class="form-label fw-semibold">Email</label><input type="email" name="email" class="form-control"></div>
                <div class="mb-3"><label class="form-label fw-semibold">Address</label><textarea name="address" class="form-control" rows="2"></textarea></div>
                <div class="form-check"><input type="checkbox" name="is_primary" class="form-check-input" value="1" id="primaryCreate"><label class="form-check-label fw-semibold" for="primaryCreate">Primary Contact</label></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save</button>
            </div>
        </form>
    </div></div>
</div>

@foreach($records as $r)
<div class="modal fade" id="editModal{{ $r->id }}" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('health.emergency-contacts.update', $r->id) }}">@csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-edit me-1"></i>Edit Contact</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label fw-semibold">Student</label><p class="form-control-plaintext">{{ $r->first_name }} {{ $r->last_name }}</p><input type="hidden" name="student_id" value="{{ $r->student_id }}"></div>
                <div class="mb-3"><label class="form-label fw-semibold">Contact Name</label><input type="text" name="contact_name" class="form-control" value="{{ $r->contact_name }}" required></div>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Relation</label><select name="relation" class="form-select" required><option value="">Select</option>@foreach(['Father','Mother','Guardian','Sibling','Relative','Other'] as $rel)<option value="{{ $rel }}" {{ $r->relation === $rel ? 'selected' : '' }}>{{ $rel }}</option>@endforeach</select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Phone</label><input type="text" name="phone" class="form-control" value="{{ $r->phone }}" required></div>
                </div>
                <div class="mb-3"><label class="form-label fw-semibold">Email</label><input type="email" name="email" class="form-control" value="{{ $r->email }}"></div>
                <div class="mb-3"><label class="form-label fw-semibold">Address</label><textarea name="address" class="form-control" rows="2">{{ $r->address }}</textarea></div>
                <div class="form-check"><input type="checkbox" name="is_primary" class="form-check-input" value="1" id="primaryEdit{{ $r->id }}" {{ $r->is_primary ? 'checked' : '' }}><label class="form-check-label fw-semibold" for="primaryEdit{{ $r->id }}">Primary Contact</label></div>
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
