@extends('layouts.app')
@section('title', 'Asset Allocation')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-hand-holding me-2"></i>Asset Allocation</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Asset Management</li><li class="breadcrumb-item active">Allocations</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>Allocate Asset</button>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label fw-semibold small">Search</label><input type="text" name="search" class="form-control form-control-sm" placeholder="Asset name or code..." value="{{ request('search') }}"></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>@if(request('search'))<a href="{{ route('asset.allocations') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-times"></i></a>@endif</div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Asset','Allocated To','Allocation Date','Expected Return','Actual Return','Actions']">
            @forelse($allocations as $al)
            <tr>
                <td>{{ $al->id }}</td>
                <td class="fw-semibold">{{ $al->asset_name }}<br><small class="text-muted">{{ $al->asset_code }}</small></td>
                <td>{{ $al->allocated_to_type }} #{{ $al->allocated_to_id }}</td>
                <td>{{ $al->allocation_date }}</td>
                <td>{{ $al->expected_return_date ?? '-' }}</td>
                <td>@if($al->actual_return_date){{ $al->actual_return_date }}@else<span class="badge bg-warning text-dark">Active</span>@endif</td>
                <td>
                    <div class="table-actions">
                        @if(!$al->actual_return_date)
                        <button class="btn btn-sm btn-outline-success" title="Return" data-bs-toggle="modal" data-bs-target="#returnModal{{ $al->id }}"><i class="fas fa-undo"></i></button>
                        @endif
                        <form method="POST" action="{{ route('asset.allocations.delete', $al->id) }}" class="d-inline" onsubmit="return confirm('Delete this allocation?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-4 text-muted">No allocations.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$allocations" />

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('asset.allocations.store') }}">@csrf
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-1"></i>Allocate Asset</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12"><label class="form-label fw-semibold">Asset</label><select name="asset_id" class="form-select" required><option value="">Select</option>@foreach($assets as $as)<option value="{{ $as->id }}">{{ $as->name }} ({{ $as->code }})</option>@endforeach</select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Allocated To Type</label><select name="allocated_to_type" class="form-select" required><option value="">Select</option><option value="employee">Employee</option><option value="student">Student</option><option value="department">Department</option></select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Allocated To ID</label><input type="number" name="allocated_to_id" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Allocation Date</label><input type="date" name="allocation_date" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Expected Return Date</label><input type="date" name="expected_return_date" class="form-control"></div>
                    <div class="col-12"><label class="form-label fw-semibold">Remarks</label><textarea name="remarks" class="form-control" rows="2"></textarea></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Allocate</button>
            </div>
        </form>
    </div></div>
</div>

@foreach($allocations as $al)
@if(!$al->actual_return_date)
<div class="modal fade" id="returnModal{{ $al->id }}" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('asset.allocations.return', $al->id) }}">@csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-undo me-1"></i>Return Asset</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <p>Asset: <strong>{{ $al->asset_name }}</strong> ({{ $al->asset_code }})</p>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Actual Return Date</label><input type="date" name="actual_return_date" class="form-control" required></div>
                    <div class="col-12"><label class="form-label fw-semibold">Condition on Return</label><textarea name="condition_on_return" class="form-control" rows="2"></textarea></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-check me-1"></i>Return</button>
            </div>
        </form>
    </div></div>
</div>
@endif
@endforeach
@endsection