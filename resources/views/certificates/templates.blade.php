@extends('layouts.app')
@section('title', 'Certificate Templates')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-palette me-2"></i>Certificate Templates</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Certificates</li><li class="breadcrumb-item active">Certificate Templates</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>Add Template</button>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Template name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>
                @if(request('search'))<a href="{{ route('certificates.templates') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-times"></i></a>@endif
            </div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Name','Type','Layout','Variables','Default','Status','Actions']">
            @forelse($templates as $t)
            <tr>
                <td>{{ $t->id }}</td>
                <td class="fw-semibold">{{ $t->name }}</td>
                <td><span class="badge bg-info">{{ $t->type ?? '-' }}</span></td>
                <td>{{ ucfirst($t->layout ?? 'portrait') }}</td>
                <td>
                    @php $vars = json_decode($t->variables, true); @endphp
                    @if($vars && is_array($vars))
                        <span class="badge bg-secondary">{{ count($vars) }} vars</span>
                    @else
                        <span class="badge bg-light text-muted">None</span>
                    @endif
                </td>
                <td>@if($t->is_default)<span class="badge bg-success">Default</span>@else<span class="badge bg-secondary">No</span>@endif</td>
                <td>@if($t->status)<span class="badge bg-success">Active</span>@else<span class="badge bg-danger">Inactive</span>@endif</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" title="Edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $t->id }}"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('certificates.templates.delete', $t->id) }}" class="d-inline" onsubmit="return confirm('Delete this template?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-4 text-muted">No templates found.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$templates" />

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <form method="POST" action="{{ route('certificates.templates.store') }}">@csrf
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-1"></i>Add Template</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Name</label><input type="text" name="name" class="form-control" required placeholder="e.g. Standard TC"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Type</label><select name="type" class="form-select" required><option value="">Select</option>@foreach($types as $t)<option value="{{ $t->code }}">{{ $t->name }} ({{ $t->code }})</option>@endforeach</select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Layout</label><select name="layout" class="form-select"><option value="portrait">Portrait</option><option value="landscape">Landscape</option></select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Orientation</label><select name="orientation" class="form-select"><option value="portrait">Portrait</option><option value="landscape">Landscape</option></select></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Margin Left</label><input type="text" name="margin_left" class="form-control" placeholder="e.g. 20mm"></div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Variables (JSON)</label>
                        <input type="text" name="variables" class="form-control" placeholder='{"student_name":"","date":""}'>
                    </div>
                    <div class="col-md-4 d-flex align-items-end gap-3 pb-1">
                        <div class="form-check"><input type="checkbox" name="is_default" class="form-check-input" value="1" id="defaultCreate"><label class="form-check-label fw-semibold" for="defaultCreate">Default</label></div>
                        <div class="form-check"><input type="checkbox" name="status" class="form-check-input" value="1" id="statusCreate" checked><label class="form-check-label fw-semibold" for="statusCreate">Active</label></div>
                    </div>
                    <div class="col-12"><label class="form-label fw-semibold">Content (HTML)</label><textarea name="content" class="form-control font-monospace" rows="8" required placeholder="<html>... use @{{variable_name}} for placeholders"></textarea></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Template</button>
            </div>
        </form>
    </div></div>
</div>

@foreach($templates as $t)
<div class="modal fade" id="editModal{{ $t->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <form method="POST" action="{{ route('certificates.templates.update', $t->id) }}">@csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-edit me-1"></i>Edit Template</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Name</label><input type="text" name="name" class="form-control" value="{{ $t->name }}" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Type</label><select name="type" class="form-select" required><option value="">Select</option>@foreach($types as $ct)<option value="{{ $ct->code }}" {{ $t->type === $ct->code ? 'selected' : '' }}>{{ $ct->name }} ({{ $ct->code }})</option>@endforeach</select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Layout</label><select name="layout" class="form-select"><option value="portrait" {{ ($t->layout ?? 'portrait') === 'portrait' ? 'selected' : '' }}>Portrait</option><option value="landscape" {{ ($t->layout ?? '') === 'landscape' ? 'selected' : '' }}>Landscape</option></select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Orientation</label><select name="orientation" class="form-select"><option value="portrait" {{ ($t->orientation ?? 'portrait') === 'portrait' ? 'selected' : '' }}>Portrait</option><option value="landscape" {{ ($t->orientation ?? '') === 'landscape' ? 'selected' : '' }}>Landscape</option></select></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Margin Left</label><input type="text" name="margin_left" class="form-control" value="{{ $t->margin_left }}"></div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Variables (JSON)</label>
                        <input type="text" name="variables" class="form-control" value="{{ is_string($t->variables) ? $t->variables : json_encode($t->variables) }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end gap-3 pb-1">
                        <div class="form-check"><input type="checkbox" name="is_default" class="form-check-input" value="1" id="defaultEdit{{ $t->id }}" {{ $t->is_default ? 'checked' : '' }}><label class="form-check-label fw-semibold" for="defaultEdit{{ $t->id }}">Default</label></div>
                        <div class="form-check"><input type="checkbox" name="status" class="form-check-input" value="1" id="statusEdit{{ $t->id }}" {{ $t->status ? 'checked' : '' }}><label class="form-check-label fw-semibold" for="statusEdit{{ $t->id }}">Active</label></div>
                    </div>
                    <div class="col-12"><label class="form-label fw-semibold">Content (HTML)</label><textarea name="content" class="form-control font-monospace" rows="8" required>{{ $t->content }}</textarea></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update Template</button>
            </div>
        </form>
    </div></div>
</div>
@endforeach
@endsection
