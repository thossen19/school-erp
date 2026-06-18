@extends('layouts.app')
@section('title', 'Asset Maintenance')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-tools me-2"></i>Asset Maintenance</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Asset Management</li><li class="breadcrumb-item active">Maintenance</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>Add Record</button>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label fw-semibold small">Search</label><input type="text" name="search" class="form-control form-control-sm" placeholder="Asset or maintenance type..." value="{{ request('search') }}"></div>
            <div class="col-md-2"><label class="form-label fw-semibold small">Status</label><select name="status" class="form-select form-select-sm"><option value="">All</option><option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option><option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option><option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option></select></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>@if(request('search')||request('status'))<a href="{{ route('asset.maintenance') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-times"></i></a>@endif</div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Asset','Type','Date','Cost','Vendor','Next Date','Status','Actions']">
            @forelse($records as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td class="fw-semibold">{{ $r->asset_name }}<br><small class="text-muted">{{ $r->asset_code }}</small></td>
                <td>{{ $r->maintenance_type }}</td>
                <td>{{ $r->maintenance_date }}</td>
                <td class="fw-bold">{{ number_format($r->cost, 2) }}</td>
                <td>{{ $r->vendor ?? '-' }}</td>
                <td>{{ $r->next_maintenance_date ?? '-' }}</td>
                <td><span class="badge bg-{{ $r->status === 'completed' ? 'success' : ($r->status === 'in_progress' ? 'info' : 'warning') }}">{{ ucfirst(str_replace('_', ' ', $r->status)) }}</span></td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" title="Edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $r->id }}"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('asset.maintenance.delete', $r->id) }}" class="d-inline" onsubmit="return confirm('Delete this record?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center py-4 text-muted">No maintenance records.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$records" />

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <form method="POST" action="{{ route('asset.maintenance.store') }}">@csrf
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-1"></i>Add Maintenance Record</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Asset</label><select name="asset_id" class="form-select" required><option value="">Select</option>@foreach($assets as $as)<option value="{{ $as->id }}">{{ $as->name }} ({{ $as->code }})</option>@endforeach</select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Maintenance Type</label><input type="text" name="maintenance_type" class="form-control" required></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Maintenance Date</label><input type="date" name="maintenance_date" class="form-control" required></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Cost</label><input type="number" step="0.01" name="cost" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Vendor</label><input type="text" name="vendor" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Next Maintenance Date</label><input type="date" name="next_maintenance_date" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Status</label><select name="status" class="form-select"><option value="pending">Pending</option><option value="in_progress">In Progress</option><option value="completed">Completed</option></select></div>
                    <div class="col-12"><label class="form-label fw-semibold">Description</label><textarea name="description" class="form-control" rows="2"></textarea></div>
                </div>
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
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <form method="POST" action="{{ route('asset.maintenance.update', $r->id) }}">@csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-edit me-1"></i>Edit Maintenance</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Maintenance Type</label><input type="text" name="maintenance_type" class="form-control" value="{{ $r->maintenance_type }}" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Maintenance Date</label><input type="date" name="maintenance_date" class="form-control" value="{{ $r->maintenance_date }}" required></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Cost</label><input type="number" step="0.01" name="cost" class="form-control" value="{{ $r->cost }}"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Vendor</label><input type="text" name="vendor" class="form-control" value="{{ $r->vendor }}"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Next Maintenance Date</label><input type="date" name="next_maintenance_date" class="form-control" value="{{ $r->next_maintenance_date }}"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Status</label><select name="status" class="form-select"><option value="pending" {{ $r->status === 'pending' ? 'selected' : '' }}>Pending</option><option value="in_progress" {{ $r->status === 'in_progress' ? 'selected' : '' }}>In Progress</option><option value="completed" {{ $r->status === 'completed' ? 'selected' : '' }}>Completed</option></select></div>
                    <div class="col-12"><label class="form-label fw-semibold">Description</label><textarea name="description" class="form-control" rows="2">{{ $r->description }}</textarea></div>
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