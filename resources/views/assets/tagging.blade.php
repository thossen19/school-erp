@extends('layouts.app')
@section('title', 'Asset Tagging')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-tags me-2"></i>Asset Tagging</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Asset Management</li><li class="breadcrumb-item active">Tagging</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label fw-semibold small">Search</label><input type="text" name="search" class="form-control form-control-sm" placeholder="Name, barcode or code..." value="{{ request('search') }}"></div>
            <div class="col-md-2"><label class="form-label fw-semibold small">Tag Status</label><select name="tag_status" class="form-select form-select-sm"><option value="">All</option><option value="tagged" {{ request('tag_status') === 'tagged' ? 'selected' : '' }}>Tagged</option><option value="untagged" {{ request('tag_status') === 'untagged' ? 'selected' : '' }}>Untagged</option></select></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>@if(request('search')||request('tag_status'))<a href="{{ route('asset.tagging') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-times"></i></a>@endif</div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Name','Code','Category','Barcode/Tag','Tag Status','Actions']">
            @forelse($assets as $a)
            <tr>
                <td>{{ $a->id }}</td>
                <td class="fw-semibold">{{ $a->name }}</td>
                <td><code>{{ $a->code }}</code></td>
                <td>{{ $a->category_name }}</td>
                <td><small>{{ $a->barcode ?? '-' }}</small></td>
                <td>@if($a->barcode)<span class="badge bg-success">Tagged</span>@else<span class="badge bg-warning text-dark">Untagged</span>@endif</td>
                <td>
                    <button class="btn btn-sm btn-outline-{{ $a->barcode ? 'info' : 'primary' }}" title="{{ $a->barcode ? 'Edit Tag' : 'Add Tag' }}" data-bs-toggle="modal" data-bs-target="#tagModal{{ $a->id }}"><i class="fas fa-{{ $a->barcode ? 'edit' : 'plus' }}"></i></button>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-4 text-muted">No assets found.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$assets" />

@foreach($assets as $a)
<div class="modal fade" id="tagModal{{ $a->id }}" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('asset.tagging.update', $a->id) }}">@csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-tag me-1"></i>{{ $a->barcode ? 'Edit' : 'Add' }} Tag</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <p>Asset: <strong>{{ $a->name }}</strong> ({{ $a->code }})</p>
                <label class="form-label fw-semibold">Barcode / Tag</label>
                <input type="text" name="barcode" class="form-control" value="{{ $a->barcode }}" placeholder="AST-XXXXXXXX">
                <div class="form-text">Leave empty to auto-generate a barcode.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Tag</button>
            </div>
        </form>
    </div></div>
</div>
@endforeach
@endsection