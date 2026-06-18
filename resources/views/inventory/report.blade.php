@extends('layouts.app')

@section('title', 'Inventory Report')

@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chart-bar me-2"></i>Inventory Report</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Inventory</li><li class="breadcrumb-item active">Report</li></ol></nav>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-primary bg-opacity-10">
            <div class="card-body text-center">
                <i class="fas fa-box fa-2x text-primary mb-2"></i>
                <h3 class="fw-bold mb-0">{{ $totalItems }}</h3>
                <small class="text-muted">Total Items</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-info bg-opacity-10">
            <div class="card-body text-center">
                <i class="fas fa-tags fa-2x text-info mb-2"></i>
                <h3 class="fw-bold mb-0">{{ $totalCategories }}</h3>
                <small class="text-muted">Categories</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-success bg-opacity-10">
            <div class="card-body text-center">
                <i class="fas fa-truck fa-2x text-success mb-2"></i>
                <h3 class="fw-bold mb-0">{{ $totalVendors }}</h3>
                <small class="text-muted">Vendors</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 bg-warning bg-opacity-10">
            <div class="card-body text-center">
                <i class="fas fa-warehouse fa-2x text-warning mb-2"></i>
                <h3 class="fw-bold mb-0">{{ $totalStock }}</h3>
                <small class="text-muted">Total Stock Units</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3"><h5 class="mb-0 fw-semibold">Stock Status</h5></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2"><span>Low Stock Items</span><span class="fw-bold text-warning">{{ $lowStockItems }}</span></div>
                <div class="d-flex justify-content-between"><span>Out of Stock</span><span class="fw-bold text-danger">{{ $outOfStock }}</span></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3"><h5 class="mb-0 fw-semibold">Purchase Orders</h5></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2"><span>Total Orders</span><span class="fw-bold">{{ $totalOrders }}</span></div>
                <div class="d-flex justify-content-between"><span>Pending</span><span class="fw-bold text-warning">{{ $pendingOrders }}</span></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3"><h5 class="mb-0 fw-semibold">Activity</h5></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2"><span>Stock Movements</span><span class="fw-bold">{{ $totalMovements }}</span></div>
                <div class="d-flex justify-content-between"><span>Audits Conducted</span><span class="fw-bold">{{ $totalAudits }}</span></div>
            </div>
        </div>
    </div>
</div>
@endsection
