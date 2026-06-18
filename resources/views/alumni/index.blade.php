@extends('layouts.app')
@section('title', 'Alumni Directory')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-graduation-cap me-2"></i>Alumni Directory</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Alumni</li><li class="breadcrumb-item active">Directory</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>Add Alumni</button>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label fw-semibold small">Search</label><input type="text" name="search" class="form-control form-control-sm" placeholder="Name or email..." value="{{ request('search') }}"></div>
            <div class="col-md-2"><label class="form-label fw-semibold small">Batch</label><select name="graduation_year" class="form-select form-select-sm"><option value="">All</option>@foreach($years as $y)<option value="{{ $y }}" {{ request('graduation_year') == $y ? 'selected' : '' }}>{{ $y }}</option>@endforeach</select></div>
            <div class="col-md-2"><label class="form-label fw-semibold small">Verified</label><select name="is_verified" class="form-select form-select-sm"><option value="">All</option><option value="1" {{ request('is_verified') === '1' ? 'selected' : '' }}>Verified</option><option value="0" {{ request('is_verified') === '0' ? 'selected' : '' }}>Unverified</option></select></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>@if(request('search')||request('graduation_year')||request('is_verified')!=='')<a href="{{ route('alumni.index') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-times"></i></a>@endif</div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Name','Email','Phone','Batch','Occupation','Company','Verified','Actions']">
            @forelse($alumni as $a)
            <tr>
                <td>{{ $a->id }}</td>
                <td class="fw-semibold">{{ $a->first_name }} {{ $a->last_name }}</td>
                <td>{{ $a->email }}</td>
                <td>{{ $a->phone ?? '-' }}</td>
                <td><span class="badge bg-secondary">{{ $a->graduation_year }}</span></td>
                <td>{{ $a->current_occupation ?? '-' }}</td>
                <td>{{ $a->company ?? '-' }}</td>
                <td>@if($a->is_verified)<span class="badge bg-success">Yes</span>@else<span class="badge bg-warning text-dark">No</span>@endif</td>
                <td>
                    <div class="table-actions">
                        @if(!$a->is_verified)
                        <form method="POST" action="{{ route('alumni.verify', $a->id) }}" class="d-inline">@csrf<button class="btn btn-sm btn-outline-success" title="Verify"><i class="fas fa-check"></i></button></form>
                        @endif
                        <button class="btn btn-sm btn-outline-info" title="Edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $a->id }}"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('alumni.destroy', $a->id) }}" class="d-inline" onsubmit="return confirm('Delete this alumni record?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center py-4 text-muted">No alumni records found.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$alumni" />

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <form method="POST" action="{{ route('alumni.store') }}">@csrf
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-1"></i>Add Alumni</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">First Name</label><input type="text" name="first_name" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Last Name</label><input type="text" name="last_name" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Email</label><input type="email" name="email" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Phone</label><input type="text" name="phone" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Graduation Year</label><input type="number" name="graduation_year" class="form-control" required></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Current Occupation</label><input type="text" name="current_occupation" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Company</label><input type="text" name="company" class="form-control"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Designation</label><input type="text" name="designation" class="form-control"></div>
                    <div class="col-md-8"><label class="form-label fw-semibold">Address</label><input type="text" name="address" class="form-control"></div>
                    <div class="col-12"><div class="form-check"><input type="checkbox" name="is_verified" class="form-check-input" value="1" id="cv"><label class="form-check-label" for="cv">Verified</label></div></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save</button>
            </div>
        </form>
    </div></div>
</div>

@foreach($alumni as $a)
<div class="modal fade" id="editModal{{ $a->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <form method="POST" action="{{ route('alumni.update', $a->id) }}">@csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-edit me-1"></i>Edit Alumni</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">First Name</label><input type="text" name="first_name" class="form-control" value="{{ $a->first_name }}" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Last Name</label><input type="text" name="last_name" class="form-control" value="{{ $a->last_name }}" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Email</label><input type="email" name="email" class="form-control" value="{{ $a->email }}" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Phone</label><input type="text" name="phone" class="form-control" value="{{ $a->phone }}"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Graduation Year</label><input type="number" name="graduation_year" class="form-control" value="{{ $a->graduation_year }}" required></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Current Occupation</label><input type="text" name="current_occupation" class="form-control" value="{{ $a->current_occupation }}"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Company</label><input type="text" name="company" class="form-control" value="{{ $a->company }}"></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Designation</label><input type="text" name="designation" class="form-control" value="{{ $a->designation }}"></div>
                    <div class="col-md-8"><label class="form-label fw-semibold">Address</label><input type="text" name="address" class="form-control" value="{{ $a->address }}"></div>
                    <div class="col-12"><div class="form-check"><input type="checkbox" name="is_verified" class="form-check-input" value="1" id="ev{{ $a->id }}" {{ $a->is_verified ? 'checked' : '' }}><label class="form-check-label" for="ev{{ $a->id }}">Verified</label></div></div>
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