@extends("layouts.app")

@section("title", "Inventory Items")

@section("content")
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-box me-2"></i>Inventory Items</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Inventory</li><li class="breadcrumb-item active">Items</li></ol></nav>
    </div>
    <a href="{{ route('inventory.items.create') }}" class="btn btn-primary btn-sm">+ Add Item</a>
</div>

<div class="filter-bar">
    <form method="GET" action="{{ route('inventory.items') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Item name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Category</label>
                <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <div class="form-check mt-3">
                    <input type="checkbox" name="low_stock" value="1" class="form-check-input" id="lowStock" {{ request('low_stock') ? 'checked' : '' }} onchange="this.form.submit()">
                    <label class="form-check-label small" for="lowStock">Low Stock Only</label>
                </div>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Name','Code','Category','Stock','Min Stock','Price','Actions']">
            @forelse($items as $item)
                <tr>
                    <td class="fw-semibold">{{ $item->name }}</td>
                    <td>{{ $item->code }}</td>
                    <td>{{ $item->category_name ?? '-' }}</td>
                    <td><span class="badge bg-{{ $item->quantity <= $item->min_quantity ? 'danger' : 'success' }}">{{ $item->quantity }}</span></td>
                    <td>{{ $item->min_quantity }}</td>
                    <td>৳{{ number_format($item->price, 2) }}</td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('inventory.items.show', $item->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('inventory.items.edit', $item->id) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-3">No items found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$items" />
</div>
@endsection
