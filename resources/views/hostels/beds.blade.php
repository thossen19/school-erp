@extends('layouts.app')
@section('title', 'Bed Management')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-bed me-2"></i>Bed Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Hostel</li><li class="breadcrumb-item active">Bed Management</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3"><label class="form-label fw-semibold small">Search</label><input type="text" name="search" class="form-control form-control-sm" placeholder="Bed or room number..." value="{{ request('search') }}"></div>
            <div class="col-md-2"><label class="form-label fw-semibold small">Hostel</label><select name="hostel_id" class="form-select form-select-sm" onchange="this.form.submit()"><option value="">All</option>@foreach($hostels as $hs)<option value="{{ $hs->id }}" {{ request('hostel_id') == $hs->id ? 'selected' : '' }}>{{ $hs->name }}</option>@endforeach</select></div>
            <div class="col-md-2"><label class="form-label fw-semibold small">Status</label><select name="status" class="form-select form-select-sm"><option value="">All</option><option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option><option value="occupied" {{ request('status') === 'occupied' ? 'selected' : '' }}>Occupied</option><option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option></select></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>@if(request('search') || request('status') || request('hostel_id'))<a href="{{ route('hostels.beds') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-times"></i></a>@endif</div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Hostel','Room','Bed Number','Status','Actions']">
            @forelse($beds as $b)
            <tr>
                <td>{{ $b->id }}</td>
                <td>{{ $b->hostel_name }}</td>
                <td>{{ $b->room_number }}</td>
                <td class="fw-semibold">{{ $b->bed_number }}</td>
                <td>
                    @php $badge = ['available' => 'success', 'occupied' => 'warning', 'maintenance' => 'danger']; @endphp
                    <span class="badge bg-{{ $badge[$b->status] ?? 'secondary' }}">{{ ucfirst($b->status) }}</span>
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-info" title="Change Status" data-bs-toggle="modal" data-bs-target="#statusModal{{ $b->id }}"><i class="fas fa-edit"></i></button>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center py-4 text-muted">No beds found.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$beds" />

@foreach($beds as $b)
<div class="modal fade" id="statusModal{{ $b->id }}" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('hostels.beds.update', $b->id) }}">@csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-edit me-1"></i>Change Bed Status</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <p>Hostel: <strong>{{ $b->hostel_name }}</strong></p>
                <p>Room: <strong>{{ $b->room_number }}</strong> | Bed: <strong>{{ $b->bed_number }}</strong></p>
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select" required>
                    <option value="available" {{ $b->status === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="occupied" {{ $b->status === 'occupied' ? 'selected' : '' }}>Occupied</option>
                    <option value="maintenance" {{ $b->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
            </div>
        </form>
    </div></div>
</div>
@endforeach
@endsection
