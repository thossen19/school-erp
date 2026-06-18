@extends('layouts.app')
@section('title', 'Hostel Fees')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-money-bill-wave me-2"></i>Hostel Fees</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Hostel</li><li class="breadcrumb-item active">Hostel Fees</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>Add Fee</button>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3"><label class="form-label fw-semibold small">Hostel</label><select name="hostel_id" class="form-select form-select-sm" onchange="this.form.submit()"><option value="">All</option>@foreach($hostels as $hs)<option value="{{ $hs->id }}" {{ request('hostel_id') == $hs->id ? 'selected' : '' }}>{{ $hs->name }}</option>@endforeach</select></div>
            <div class="col-md-2">@if(request('hostel_id'))<a href="{{ route('hostels.fees') }}" class="btn btn-outline-secondary btn-sm mt-4"><i class="fas fa-times"></i> Clear</a>@endif</div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Name','Hostel','Room Type','Amount','Frequency','Academic Year','Actions']">
            @forelse($fees as $f)
            <tr>
                <td>{{ $f->id }}</td>
                <td class="fw-semibold">{{ $f->name ?? '-' }}</td>
                <td>{{ $f->hostel_name }}</td>
                <td>{{ $f->room_type ?? 'All' }}</td>
                <td class="fw-bold">{{ number_format($f->fee_amount, 2) }}</td>
                <td><span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $f->frequency ?? 'monthly')) }}</span></td>
                <td>{{ $f->academic_year ?? '-' }}</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" title="Edit" data-bs-toggle="modal" data-bs-target="#editModal{{ $f->id }}"><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('hostels.fees.delete', $f->id) }}" class="d-inline" onsubmit="return confirm('Delete this fee structure?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-4 text-muted">No fee structures found.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$fees" />

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('hostels.fees.store') }}">@csrf
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-1"></i>Add Fee Structure</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label fw-semibold">Hostel</label><select name="hostel_id" class="form-select" required><option value="">Select</option>@foreach($hostels as $hs)<option value="{{ $hs->id }}">{{ $hs->name }}</option>@endforeach</select></div>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Name</label><input type="text" name="name" class="form-control" placeholder="e.g. Hostel Fee 2026"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Room Type</label><input type="text" name="room_type" class="form-control" placeholder="e.g. Standard, Deluxe"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Fee Amount</label><input type="number" step="0.01" name="fee_amount" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Frequency</label><select name="frequency" class="form-select"><option value="monthly">Monthly</option><option value="quarterly">Quarterly</option><option value="half_yearly">Half Yearly</option><option value="yearly">Yearly</option><option value="one_time">One Time</option></select></div>
                    <div class="col-12"><label class="form-label fw-semibold">Academic Year</label><select name="academic_year_id" class="form-select" required><option value="">Select</option>@foreach($academicYears as $ay)<option value="{{ $ay->id }}">{{ $ay->name }}</option>@endforeach</select></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save</button>
            </div>
        </form>
    </div></div>
</div>

@foreach($fees as $f)
<div class="modal fade" id="editModal{{ $f->id }}" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('hostels.fees.update', $f->id) }}">@csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-edit me-1"></i>Edit Fee Structure</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label fw-semibold">Hostel</label><select name="hostel_id" class="form-select" required><option value="">Select</option>@foreach($hostels as $hs)<option value="{{ $hs->id }}" {{ $f->hostel_id == $hs->id ? 'selected' : '' }}>{{ $hs->name }}</option>@endforeach</select></div>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Name</label><input type="text" name="name" class="form-control" value="{{ $f->name }}"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Room Type</label><input type="text" name="room_type" class="form-control" value="{{ $f->room_type }}"></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Fee Amount</label><input type="number" step="0.01" name="fee_amount" class="form-control" value="{{ $f->fee_amount }}" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Frequency</label><select name="frequency" class="form-select"><option value="monthly" {{ $f->frequency === 'monthly' ? 'selected' : '' }}>Monthly</option><option value="quarterly" {{ $f->frequency === 'quarterly' ? 'selected' : '' }}>Quarterly</option><option value="half_yearly" {{ $f->frequency === 'half_yearly' ? 'selected' : '' }}>Half Yearly</option><option value="yearly" {{ $f->frequency === 'yearly' ? 'selected' : '' }}>Yearly</option><option value="one_time" {{ $f->frequency === 'one_time' ? 'selected' : '' }}>One Time</option></select></div>
                    <div class="col-12"><label class="form-label fw-semibold">Academic Year</label><select name="academic_year_id" class="form-select" required><option value="">Select</option>@foreach($academicYears as $ay)<option value="{{ $ay->id }}" {{ $f->academic_year_id == $ay->id ? 'selected' : '' }}>{{ $ay->name }}</option>@endforeach</select></div>
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
