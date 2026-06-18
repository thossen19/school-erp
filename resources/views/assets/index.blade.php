@extends('layouts.app')
@section('title', 'Asset Registration')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-building me-2"></i>Asset Registration</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Asset Management</li><li class="breadcrumb-item active">Registration</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>Register Asset</button>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label fw-semibold small">Search</label><input type="text" name="search" class="form-control form-control-sm" placeholder="Name, code, barcode..." value="{{ request('search') }}"></div>
            <div class="col-md-2"><label class="form-label fw-semibold small">Category</label><select name="category_id" class="form-select form-select-sm"><option value="">All</option>@foreach($categories as $c)<option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>@endforeach</select></div>
            <div class="col-md-2"><label class="form-label fw-semibold small">Status</label><select name="status" class="form-select form-select-sm"><option value="">All</option><option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option><option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option><option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option><option value="disposed" {{ request('status') === 'disposed' ? 'selected' : '' }}>Disposed</option><option value="missing" {{ request('status') === 'missing' ? 'selected' : '' }}>Missing</option></select></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>@if(request('search')||request('category_id')||request('status'))<a href="{{ route('asset.index') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-times"></i></a>@endif</div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Name','Code','Category','Barcode','Location','Value','Status','Actions']">
            @forelse($assets as $a)
            <tr>
                <td>{{ $a->id }}</td>
                <td class="fw-semibold">{{ $a->name }}</td>
                <td><code>{{ $a->code }}</code></td>
                <td>{{ $a->category_name }}</td>
                <td><small>{{ $a->barcode ?? '-' }}</small></td>
                <td>{{ $a->location ?? '-' }}</td>
                <td class="fw-bold">{{ number_format($a->current_value, 0) }}</td>
                <td><span class="badge bg-{{ $a->status === 'active' ? 'success' : ($a->status === 'maintenance' ? 'warning' : ($a->status === 'missing' ? 'danger' : 'secondary')) }}">{{ ucfirst($a->status) }}</span></td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" title="Edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $a->id }}"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('asset.destroy', $a->id) }}" class="d-inline" onsubmit="return confirm('Delete this asset?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center py-4 text-muted">No assets registered.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$assets" />

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <form method="POST" action="{{ route('asset.store') }}">@csrf
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-1"></i>Register Asset</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Name</label><input type="text" name="name" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Code</label><input type="text" name="code" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Category</label><select name="category_id" class="form-select" required><option value="">Select</option>@foreach($categories as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Serial Number</label><input type="text" name="serial_number" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Purchase Date</label><input type="date" name="purchase_date" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Purchase Price</label><input type="number" step="0.01" name="purchase_price" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Current Value</label><input type="number" step="0.01" name="current_value" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Salvage Value</label><input type="number" step="0.01" name="salvage_value" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Useful Life (months)</label><input type="number" name="useful_life" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Depreciation Rate (%)</label><input type="number" step="0.01" name="depreciation_rate" class="form-control"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Location</label><input type="text" name="location" class="form-control"></div>
                    <div class="col-12"><label class="form-label fw-semibold">Description</label><textarea name="description" class="form-control" rows="2"></textarea></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Register</button>
            </div>
        </form>
    </div></div>
</div>

@foreach($assets as $a)
<div class="modal fade" id="editModal{{ $a->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <form method="POST" action="{{ route('asset.update', $a->id) }}">@csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-edit me-1"></i>Edit Asset</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Name</label><input type="text" name="name" class="form-control" value="{{ $a->name }}" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Code</label><input type="text" name="code" class="form-control" value="{{ $a->code }}" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Category</label><select name="category_id" class="form-select" required>@foreach($categories as $c)<option value="{{ $c->id }}" {{ $a->category_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>@endforeach</select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Serial Number</label><input type="text" name="serial_number" class="form-control" value="{{ $a->serial_number }}"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Purchase Date</label><input type="date" name="purchase_date" class="form-control" value="{{ $a->purchase_date }}"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Purchase Price</label><input type="number" step="0.01" name="purchase_price" class="form-control" value="{{ $a->purchase_price }}"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Current Value</label><input type="number" step="0.01" name="current_value" class="form-control" value="{{ $a->current_value }}"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Salvage Value</label><input type="number" step="0.01" name="salvage_value" class="form-control" value="{{ $a->salvage_value }}"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Useful Life (months)</label><input type="number" name="useful_life" class="form-control" value="{{ $a->useful_life }}"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Location</label><input type="text" name="location" class="form-control" value="{{ $a->location }}"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Status</label><select name="status" class="form-select"><option value="active" {{ $a->status === 'active' ? 'selected' : '' }}>Active</option><option value="inactive" {{ $a->status === 'inactive' ? 'selected' : '' }}>Inactive</option><option value="maintenance" {{ $a->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option><option value="disposed" {{ $a->status === 'disposed' ? 'selected' : '' }}>Disposed</option><option value="missing" {{ $a->status === 'missing' ? 'selected' : '' }}>Missing</option></select></div>
                    <div class="col-12"><label class="form-label fw-semibold">Description</label><textarea name="description" class="form-control" rows="2">{{ $a->description }}</textarea></div>
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