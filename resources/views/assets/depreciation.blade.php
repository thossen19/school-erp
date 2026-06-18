@extends('layouts.app')
@section('title', 'Depreciation')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chart-line me-2"></i>Depreciation</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Asset Management</li><li class="breadcrumb-item active">Depreciation</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>Record Depreciation</button>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label fw-semibold small">Search</label><input type="text" name="search" class="form-control form-control-sm" placeholder="Asset name or code..." value="{{ request('search') }}"></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>@if(request('search'))<a href="{{ route('asset.depreciation') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-times"></i></a>@endif</div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Asset','Date','Amount','Accumulated','Net Book Value','Actions']">
            @forelse($depreciations as $d)
            <tr>
                <td>{{ $d->id }}</td>
                <td class="fw-semibold">{{ $d->asset_name }}<br><small class="text-muted">{{ $d->asset_code }}</small></td>
                <td>{{ $d->depreciation_date }}</td>
                <td class="fw-bold text-danger">{{ number_format($d->amount, 2) }}</td>
                <td>{{ number_format($d->accumulated_depreciation, 2) }}</td>
                <td class="fw-bold">{{ number_format($d->net_book_value, 2) }}</td>
                <td>
                    <form method="POST" action="{{ route('asset.depreciation.delete', $d->id) }}" class="d-inline" onsubmit="return confirm('Delete this depreciation entry?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-4 text-muted">No depreciation records.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$depreciations" />

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('asset.depreciation.store') }}">@csrf
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-1"></i>Record Depreciation</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12"><label class="form-label fw-semibold">Asset</label><select name="asset_id" class="form-select" required><option value="">Select</option>@foreach($assets as $as)<option value="{{ $as->id }}">{{ $as->name }} ({{ $as->code }}) - Current: {{ number_format($as->current_value, 0) }}</option>@endforeach</select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Depreciation Date</label><input type="date" name="depreciation_date" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Amount</label><input type="number" step="0.01" name="amount" class="form-control" required></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Record</button>
            </div>
        </form>
    </div></div>
</div>
@endsection