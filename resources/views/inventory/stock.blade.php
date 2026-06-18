@extends('layouts.app')

@section('title', 'Stock Management')

@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-warehouse me-2"></i>Stock Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Inventory</li><li class="breadcrumb-item active">Stock</li></ol></nav>
    </div>
</div>

<div class="filter-bar">
    <form method="GET" action="{{ route('inventory.stock') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Item name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Filter</label>
                <select name="filter" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Items</option>
                    <option value="low" {{ request('filter') == 'low' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out" {{ request('filter') == 'out' ? 'selected' : '' }}>Out of Stock</option>
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
        <x-table :headers="['Item','Code','Category','Stock','Min Stock','Status']">
            @forelse($items as $item)
                @php
                    $status = $item->quantity <= 0 ? 'out' : ($item->quantity <= $item->min_quantity ? 'low' : 'ok');
                    $badge = ['ok' => 'success', 'low' => 'warning', 'out' => 'danger'];
                @endphp
                <tr>
                    <td class="fw-semibold"><a href="{{ route('inventory.items.show', $item->id) }}">{{ $item->name }}</a></td>
                    <td>{{ $item->code }}</td>
                    <td>{{ $item->category_name ?? '-' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->min_quantity }}</td>
                    <td><span class="badge bg-{{ $badge[$status] }}">{{ ucfirst($status) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-3">No items found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$items" />
</div>
@endsection
