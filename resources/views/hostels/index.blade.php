@extends('layouts.app')
@section('title', 'Hostel Setup')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-building me-2"></i>Hostel Setup</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Hostel</li><li class="breadcrumb-item active">Hostel Setup</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>Add Hostel</button>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label fw-semibold small">Search</label><input type="text" name="search" class="form-control form-control-sm" placeholder="Hostel name or code..." value="{{ request('search') }}"></div>
            <div class="col-md-2"><label class="form-label fw-semibold small">Status</label><select name="status" class="form-select form-select-sm"><option value="">All</option><option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option><option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option></select></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>@if(request('search') || request('status') !== null)<a href="{{ route('hostels.index') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-times"></i></a>@endif</div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Name','Code','Type','Rooms','Beds','Status','Actions']">
            @forelse($hostels as $h)
            <tr>
                <td>{{ $h->id }}</td>
                <td class="fw-semibold">{{ $h->name }}</td>
                <td class="font-monospace">{{ $h->code }}</td>
                <td><span class="badge bg-info">{{ ucfirst($h->type) }}</span></td>
                <td>{{ $h->total_rooms ?? 0 }}</td>
                <td>{{ $h->total_beds ?? 0 }}</td>
                <td>@if($h->status)<span class="badge bg-success">Active</span>@else<span class="badge bg-danger">Inactive</span>@endif</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" title="Edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $h->id }}"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('hostels.destroy', $h->id) }}" class="d-inline" onsubmit="return confirm('Delete this hostel?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-4 text-muted">No hostels found.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$hostels" />

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <form method="POST" action="{{ route('hostels.store') }}">@csrf
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-1"></i>Add Hostel</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Name</label><input type="text" name="name" class="form-control" required></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Code</label><input type="text" name="code" class="form-control" placeholder="Auto if empty"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Type</label><select name="type" class="form-select" required><option value="boys">Boys</option><option value="girls">Girls</option><option value="coed">Co-Ed</option></select></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">City</label><input type="text" name="city" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Phone</label><input type="text" name="phone" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Email</label><input type="email" name="email" class="form-control"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Total Rooms</label><input type="number" name="total_rooms" class="form-control" min="0"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Total Beds</label><input type="number" name="total_beds" class="form-control" min="0"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Warden ID</label><input type="number" name="warden_id" class="form-control" placeholder="Employee ID"></div>
                    <div class="col-md-3 d-flex align-items-end pb-1"><div class="form-check"><input type="checkbox" name="status" class="form-check-input" value="1" id="statusCreate" checked><label class="form-check-label fw-semibold" for="statusCreate">Active</label></div></div>
                    <div class="col-12"><label class="form-label fw-semibold">Address</label><textarea name="address" class="form-control" rows="2"></textarea></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save</button>
            </div>
        </form>
    </div></div>
</div>

@foreach($hostels as $h)
<div class="modal fade" id="editModal{{ $h->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <form method="POST" action="{{ route('hostels.update', $h->id) }}">@csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-edit me-1"></i>Edit Hostel</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Name</label><input type="text" name="name" class="form-control" value="{{ $h->name }}" required></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Code</label><input type="text" name="code" class="form-control" value="{{ $h->code }}"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Type</label><select name="type" class="form-select" required><option value="boys" {{ $h->type === 'boys' ? 'selected' : '' }}>Boys</option><option value="girls" {{ $h->type === 'girls' ? 'selected' : '' }}>Girls</option><option value="coed" {{ $h->type === 'coed' ? 'selected' : '' }}>Co-Ed</option></select></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">City</label><input type="text" name="city" class="form-control" value="{{ $h->city }}"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Phone</label><input type="text" name="phone" class="form-control" value="{{ $h->phone }}"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Email</label><input type="email" name="email" class="form-control" value="{{ $h->email }}"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Total Rooms</label><input type="number" name="total_rooms" class="form-control" value="{{ $h->total_rooms ?? 0 }}" min="0"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Total Beds</label><input type="number" name="total_beds" class="form-control" value="{{ $h->total_beds ?? 0 }}" min="0"></div>
                    <div class="col-md-3"><label class="form-label fw-semibold">Warden ID</label><input type="number" name="warden_id" class="form-control" value="{{ $h->warden_id }}" placeholder="Employee ID"></div>
                    <div class="col-md-3 d-flex align-items-end pb-1"><div class="form-check"><input type="checkbox" name="status" class="form-check-input" value="1" id="statusEdit{{ $h->id }}" {{ $h->status ? 'checked' : '' }}><label class="form-check-label fw-semibold" for="statusEdit{{ $h->id }}">Active</label></div></div>
                    <div class="col-12"><label class="form-label fw-semibold">Address</label><textarea name="address" class="form-control" rows="2">{{ $h->address }}</textarea></div>
                </div>
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
