@extends('layouts.app')
@section('title', 'Inventory Item Detail')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-box me-2"></i>Item Detail</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('inventory.index') }}">Inventory</a></li><li class="breadcrumb-item active">Notebooks</li></ol></nav>
    </div>
    <div class="d-flex gap-2"><a href="{{ route('inventory.edit',1) }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i>Edit</a></div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4"><small class="text-muted d-block">Item Name</small><span class="fw-semibold">Notebooks (A4 Ruled)</span></div>
            <div class="col-md-4"><small class="text-muted d-block">SKU</small><span>SKU-0001</span></div>
            <div class="col-md-4"><small class="text-muted d-block">Category</small><span class="badge bg-info">Stationery</span></div>
            <div class="col-md-3"><small class="text-muted d-block">Quantity in Stock</small><span class="fw-bold fs-5 {{ 150<20?'text-danger':'text-success' }}">150 pcs</span></div>
            <div class="col-md-3"><small class="text-muted d-block">Unit Price</small><span class="fw-bold">$2.50</span></div>
            <div class="col-md-3"><small class="text-muted d-block">Total Value</small><span class="fw-bold text-primary">$375.00</span></div>
            <div class="col-md-3"><small class="text-muted d-block">Reorder Level</small><span>10 pcs</span></div>
            <div class="col-md-4"><small class="text-muted d-block">Vendor</small><span>Vendor 1 - Office Supplies Inc.</span></div>
            <div class="col-md-4"><small class="text-muted d-block">Location</small><span>Store Room A, Shelf 3</span></div>
            <div class="col-12"><small class="text-muted d-block">Description</small><span>A4 ruled notebooks for student use</span></div>
        </div>
    </div>
</div>
@endsection