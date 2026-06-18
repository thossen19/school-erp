@extends('layouts.app')

@section('title', 'Item Details')

@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-box me-2"></i>Item Details</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('inventory.items') }}">Items</a></li><li class="breadcrumb-item active">{{ $item->name }}</li></ol></nav>
    </div>
    <div>
        <a href="{{ route('inventory.items.edit', $item->id) }}" class="btn btn-info btn-sm"><i class="fas fa-edit me-1"></i>Edit</a>
        <a href="{{ route('inventory.items') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i>Back</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3"><h5 class="mb-0 fw-semibold">Item Information</h5></div>
            <div class="card-body">
                <div class="row mb-3"><div class="col-4 text-muted small">Name</div><div class="col-8 fw-medium">{{ $item->name }}</div></div>
                <div class="row mb-3"><div class="col-4 text-muted small">Code</div><div class="col-8">{{ $item->code }}</div></div>
                <div class="row mb-3"><div class="col-4 text-muted small">Category</div><div class="col-8">{{ $item->category_name ?? '-' }}</div></div>
                <div class="row mb-3"><div class="col-4 text-muted small">Description</div><div class="col-8">{{ $item->description ?? '-' }}</div></div>
                <div class="row mb-3"><div class="col-4 text-muted small">Unit</div><div class="col-8">{{ $item->unit ?? '-' }}</div></div>
                <div class="row mb-3"><div class="col-4 text-muted small">Price</div><div class="col-8">৳{{ number_format($item->price, 2) }}</div></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3"><h5 class="mb-0 fw-semibold">Stock Information</h5></div>
            <div class="card-body">
                <div class="row mb-3"><div class="col-4 text-muted small">Current Stock</div><div class="col-8"><span class="badge bg-{{ $item->quantity <= $item->min_quantity ? 'danger' : 'success' }} fs-6">{{ $item->quantity }}</span></div></div>
                <div class="row mb-3"><div class="col-4 text-muted small">Min Stock Level</div><div class="col-8">{{ $item->min_quantity }}</div></div>
                <div class="row mb-3"><div class="col-4 text-muted small">Max Stock Level</div><div class="col-8">{{ $item->max_stock_level ?? '-' }}</div></div>
                <div class="row"><div class="col-4 text-muted small">Barcode</div><div class="col-8 font-monospace">{{ $item->barcode ?? '-' }}</div></div>
            </div>
        </div>
    </div>
    @if(count($movements) > 0)
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3"><h5 class="mb-0 fw-semibold">Stock Movements</h5></div>
                <div class="card-body p-0">
                    <x-table :headers="['Date','Type','Quantity','Reference','Notes']">
                        @foreach($movements as $m)
                            <tr>
                                <td>{{ $m->movement_date ? date('d M Y', strtotime($m->movement_date)) : '-' }}</td>
                                <td><span class="badge bg-{{ $m->type === 'in' ? 'success' : 'danger' }}">{{ ucfirst($m->type) }}</span></td>
                                <td>{{ $m->quantity }}</td>
                                <td>{{ $m->reference_type ?? '-' }}</td>
                                <td>{{ $m->remarks ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </x-table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
