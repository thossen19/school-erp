@extends('layouts.app')

@section('title', 'Vendors')

@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-truck me-2"></i>Vendors</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Inventory</li><li class="breadcrumb-item active">Vendors</li></ol></nav>
    </div>
    <a href="{{ route('inventory.vendors.create') }}" class="btn btn-primary btn-sm">+ Add Vendor</a>
</div>

<div class="filter-bar">
    <form method="GET" action="{{ route('inventory.vendors') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Vendor name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Name','Code','Contact Person','Phone','Email','Status']">
            @forelse($vendors as $v)
                <tr>
                    <td class="fw-semibold">{{ $v->name }}</td>
                    <td>{{ $v->code }}</td>
                    <td>{{ $v->contact_person ?? '-' }}</td>
                    <td>{{ $v->phone }}</td>
                    <td>{{ $v->email ?? '-' }}</td>
                    <td><span class="badge bg-{{ $v->status ? 'success' : 'danger' }}">{{ $v->status ? 'Active' : 'Inactive' }}</span></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-3">No vendors found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$vendors" />
</div>
@endsection
