@extends('layouts.app')

@section('title', 'Purchase Orders')

@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-shopping-cart me-2"></i>Purchase Orders</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Inventory</li><li class="breadcrumb-item active">Purchase Orders</li></ol></nav>
    </div>
</div>

<div class="filter-bar">
    <form method="GET" action="{{ route('inventory.purchaseOrders') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Status</label>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Order No','Vendor','Order Date','Expected Delivery','Total','Status']">
            @forelse($orders as $order)
                <tr>
                    <td class="fw-semibold">{{ $order->order_number }}</td>
                    <td>{{ $order->vendor_name ?? '-' }}</td>
                    <td>{{ $order->order_date ? date('d M Y', strtotime($order->order_date)) : '-' }}</td>
                    <td>{{ $order->expected_delivery ? date('d M Y', strtotime($order->expected_delivery)) : '-' }}</td>
                    <td>৳{{ number_format($order->total_amount ?? 0, 2) }}</td>
                    <td><span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">{{ ucfirst($order->status) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-3">No purchase orders found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$orders" />
</div>
@endsection
