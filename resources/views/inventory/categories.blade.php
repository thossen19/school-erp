@extends('layouts.app')

@section('title', 'Item Categories')

@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-tags me-2"></i>Item Categories</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Inventory</li><li class="breadcrumb-item active">Categories</li></ol></nav>
    </div>
</div>

<div class="filter-bar">
    <form method="GET" action="{{ route('inventory.categories') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Category name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Name','Code','Description','Total Items']">
            @forelse($categories as $cat)
                <tr>
                    <td class="fw-semibold">{{ $cat->name }}</td>
                    <td>{{ $cat->code }}</td>
                    <td>{{ $cat->description ?? '-' }}</td>
                    <td>{{ $cat->items_count ?? 0 }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-3">No categories found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$categories" />
</div>
@endsection
