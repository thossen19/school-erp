@extends('layouts.app')
@section('title', 'Barcode Tracking')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-barcode me-2"></i>Barcode Tracking</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Asset Management</li><li class="breadcrumb-item active">Barcode Tracking</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-6"><label class="form-label fw-semibold small">Search by Barcode, Name or Code</label><input type="text" name="search" class="form-control form-control-sm" placeholder="Scan or type barcode..." value="{{ request('search') }}"></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Search</button>@if(request('search'))<a href="{{ route('asset.barcode-tracking') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-times"></i></a>@endif</div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Barcode','Name','Code','Category','Status','Value']">
            @forelse($assets as $a)
            <tr>
                <td><code class="fw-bold">{{ $a->barcode ?? '-' }}</code></td>
                <td class="fw-semibold">{{ $a->name }}</td>
                <td><code>{{ $a->code }}</code></td>
                <td>{{ $a->category_name }}</td>
                <td><span class="badge bg-{{ $a->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($a->status) }}</span></td>
                <td class="fw-bold">{{ number_format($a->current_value, 0) }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-4 text-muted">No tagged assets found. <a href="{{ route('asset.tagging') }}">Tag assets here</a>.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$assets" />
@endsection