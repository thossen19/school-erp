@extends('layouts.app')
@section('title', 'Asset Audit')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-clipboard-check me-2"></i>Asset Audit</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Asset Management</li><li class="breadcrumb-item active">Audit</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>New Audit</button>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label fw-semibold small">Search</label><input type="text" name="search" class="form-control form-control-sm" placeholder="Asset name or code..." value="{{ request('search') }}"></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>@if(request('search'))<a href="{{ route('asset.audit') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-times"></i></a>@endif</div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Asset','Audit Date','Auditor','Condition','Location','Missing','Actions']">
            @forelse($audits as $au)
            <tr>
                <td>{{ $au->id }}</td>
                <td class="fw-semibold">{{ $au->asset_name }}<br><small class="text-muted">{{ $au->asset_code }}</small></td>
                <td>{{ $au->audit_date }}</td>
                <td>{{ $au->auditor_name ?? '-' }}</td>
                <td><span class="badge bg-{{ $au->condition === 'good' ? 'success' : ($au->condition === 'fair' ? 'warning' : ($au->condition === 'damaged' ? 'danger' : 'secondary')) }}">{{ ucfirst($au->condition ?? 'N/A') }}</span></td>
                <td><small>{{ $au->actual_location ?? $au->location ?? '-' }}</small></td>
                <td>@if($au->is_missing)<span class="badge bg-danger">Yes</span>@else<span class="badge bg-success">No</span>@endif</td>
                <td>
                    <form method="POST" action="{{ route('asset.audit.delete', $au->id) }}" class="d-inline" onsubmit="return confirm('Delete this audit record?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-4 text-muted">No audit records.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$audits" />

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('asset.audit.store') }}">@csrf
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-1"></i>New Audit Entry</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12"><label class="form-label fw-semibold">Asset</label><select name="asset_id" class="form-select" required><option value="">Select</option>@foreach($assets as $as)<option value="{{ $as->id }}">{{ $as->name }} ({{ $as->code }})</option>@endforeach</select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Audit Date</label><input type="date" name="audit_date" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Condition</label><select name="condition" class="form-select"><option value="">Select</option><option value="good">Good</option><option value="fair">Fair</option><option value="damaged">Damaged</option><option value="obsolete">Obsolete</option></select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Physical Condition</label><input type="text" name="physical_condition" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Actual Location</label><input type="text" name="actual_location" class="form-control"></div>
                    <div class="col-md-6"><div class="form-check mt-4"><input type="checkbox" name="is_missing" class="form-check-input" value="1" id="mis"><label class="form-check-label" for="mis">Asset Missing</label></div></div>
                    <div class="col-12"><label class="form-label fw-semibold">Remarks</label><textarea name="remarks" class="form-control" rows="2"></textarea></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save</button>
            </div>
        </form>
    </div></div>
</div>
@endsection