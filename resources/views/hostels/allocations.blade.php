@extends('layouts.app')
@section('title', 'Room Allocation')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-door-open me-2"></i>Room Allocation</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Hostel</li><li class="breadcrumb-item active">Room Allocation</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>Allocate Room</button>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label fw-semibold small">Search</label><input type="text" name="search" class="form-control form-control-sm" placeholder="Student name or admission no..." value="{{ request('search') }}"></div>
            <div class="col-md-2"><label class="form-label fw-semibold small">Hostel</label><select name="hostel_id" class="form-select form-select-sm"><option value="">All</option>@foreach($hostels as $hs)<option value="{{ $hs->id }}" {{ request('hostel_id') == $hs->id ? 'selected' : '' }}>{{ $hs->name }}</option>@endforeach</select></div>
            <div class="col-md-2"><label class="form-label fw-semibold small">Status</label><select name="status" class="form-select form-select-sm"><option value="">All</option><option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option><option value="checked_out" {{ request('status') === 'checked_out' ? 'selected' : '' }}>Checked Out</option></select></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>@if(request('search') || request('status') || request('hostel_id'))<a href="{{ route('hostels.allocations') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-times"></i></a>@endif</div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Student','Hostel','Room','Bed','Check In','Check Out','Status','Actions']">
            @forelse($allocations as $a)
            <tr>
                <td>{{ $a->id }}</td>
                <td class="fw-semibold">{{ $a->first_name }} {{ $a->last_name }}<br><small class="text-muted">{{ $a->admission_no }}</small></td>
                <td>{{ $a->hostel_name }}</td>
                <td>{{ $a->room_number ?? '-' }}</td>
                <td>{{ $a->bed_number ?? '-' }}</td>
                <td>{{ $a->check_in_date }}</td>
                <td>{{ $a->check_out_date ?? '-' }}</td>
                <td>@php $b = ['active' => 'success', 'checked_out' => 'secondary']; @endphp<span class="badge bg-{{ $b[$a->status] ?? 'warning' }}">{{ ucfirst($a->status) }}</span></td>
                <td>
                    <div class="table-actions">
                        @if($a->status === 'active')
                        <button class="btn btn-sm btn-outline-success" title="Check Out" data-bs-toggle="modal" data-bs-target="#checkoutModal{{ $a->id }}"><i class="fas fa-sign-out-alt"></i></button>
                        @endif
                        <form method="POST" action="{{ route('hostels.allocations.delete', $a->id) }}" class="d-inline" onsubmit="return confirm('Delete this allocation?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center py-4 text-muted">No allocations found.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$allocations" />

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <form method="POST" action="{{ route('hostels.allocations.store') }}">@csrf
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-1"></i>Allocate Room</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Student</label><select name="student_id" class="form-select" required><option value="">Select</option>@foreach($students as $s)<option value="{{ $s->id }}">{{ $s->first_name }} {{ $s->last_name }} ({{ $s->admission_no }})</option>@endforeach</select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Hostel</label><select name="hostel_id" class="form-select" required><option value="">Select</option>@foreach($hostels as $hs)<option value="{{ $hs->id }}">{{ $hs->name }}</option>@endforeach</select></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Room</label><select name="room_id" class="form-select" required><option value="">Select</option>@foreach($rooms as $r)<option value="{{ $r->id }}" data-hostel="{{ $r->hostel_id }}">{{ $r->room_number }} (Cap: {{ $r->capacity }})</option>@endforeach</select></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Bed</label><select name="bed_id" class="form-select"><option value="">Optional</option>@foreach($beds as $b)<option value="{{ $b->id }}" data-room="{{ $b->hostel_room_id }}">{{ $b->bed_number }}</option>@endforeach</select></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Check In Date</label><input type="date" name="check_in_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Expected Checkout</label><input type="date" name="expected_checkout_date" class="form-control"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Allocate</button>
            </div>
        </form>
    </div></div>
</div>

@foreach($allocations as $a)
@if($a->status === 'active')
<div class="modal fade" id="checkoutModal{{ $a->id }}" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('hostels.allocations.update', $a->id) }}">@csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-sign-out-alt me-1"></i>Check Out</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <p>Student: <strong>{{ $a->first_name }} {{ $a->last_name }}</strong></p>
                <p>Hostel: <strong>{{ $a->hostel_name }}</strong> | Room: <strong>{{ $a->room_number }}</strong> | Bed: <strong>{{ $a->bed_number ?? '-' }}</strong></p>
                <input type="hidden" name="status" value="checked_out">
                <label class="form-label fw-semibold">Check Out Date</label><input type="date" name="check_out_date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-check me-1"></i>Confirm Check Out</button>
            </div>
        </form>
    </div></div>
</div>
@endif
@endforeach
@endsection
