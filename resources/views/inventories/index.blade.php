@extends('layouts.app')
@section('title', 'Inventory')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-boxes me-2"></i>Inventory Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Inventory</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('inventory.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Add Item</a>
        <a href="{{ route('inventory.purchase-orders') }}" class="btn btn-outline-info"><i class="fas fa-shopping-cart me-1"></i>Purchase Orders</a>
        <a href="{{ route('inventory.vendors') }}" class="btn btn-outline-warning"><i class="fas fa-truck me-1"></i>Vendors</a>
        <a href="{{ route('inventory.stock-audit') }}" class="btn btn-outline-success"><i class="fas fa-clipboard-list me-1"></i>Stock Audit</a>
    </div>
</div>

<div class="filter-bar">
    <div class="row g-2 align-items-end">
        <div class="col-md-3"><label class="form-label fw-semibold small">Search</label><input type="text" class="form-control form-control-sm" placeholder="Item name..."></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Category</label><select class="form-select form-select-sm"><option>All</option><option>Stationery</option><option>Furniture</option><option>Electronics</option></select></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Status</label><select class="form-select form-select-sm"><option>All</option><option>In Stock</option><option>Low Stock</option><option>Out of Stock</option></select></div>
        <div class="col-md-1"><button class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button></div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Item Name','SKU','Category','Quantity','Unit','Unit Price','Total Value','Status','Actions']">
            @foreach(range(1,8) as $i)
            @php $qty = rand(0,200); $price = rand(5,500); @endphp
            <tr>
                <td>{{ $i }}</td>
                <td class="fw-semibold">{{ ['Notebooks','Pens','Desks','Chairs','Whiteboards','Markers','Computers','Printers'][$i-1] }}</td>
                <td>SKU-{{ sprintf('%04d',$i) }}</td>
                <td>{{ ['Stationery','Stationery','Furniture','Furniture','Stationery','Stationery','Electronics','Electronics'][$i-1] }}</td>
                <td>{{ $qty }}</td>
                <td>{{ ['pcs','pcs','units','units','pcs','pcs','units','units'][$i-1] }}</td>
                <td>${{ number_format($price,2) }}</td>
                <td class="fw-bold">${{ number_format($qty*$price,2) }}</td>
                <td>
                    <span class="badge bg-{{ $qty==0?'danger':($qty<20?'warning':'success') }}">
                        {{ $qty==0?'Out of Stock':($qty<20?'Low Stock':'In Stock') }}
                    </span>
                </td>
                <td>
                    <div class="table-actions">
                        <a href="{{ route('inventory.show',$i) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('inventory.edit',$i) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a>
                    </div>
                </td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
@endsection