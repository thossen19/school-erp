@extends('layouts.app')
@section('title', 'Visitor Tracking')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-user-friends me-2"></i>Visitor Tracking</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Hostel</li><li class="breadcrumb-item active">Visitor Tracking</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>Add Visitor</button>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label fw-semibold small">Search</label><input type="text" name="search" class="form-control form-control-sm" placeholder="Visitor name, phone or student..." value="{{ request('search') }}"></div>
            <div class="col-md-2"><label class="form-label fw-semibold small">Hostel</label><select name="hostel_id" class="form-select form-select-sm"><option value="">All</option>@foreach($hostels as $hs)<option value="{{ $hs->id }}" {{ request('hostel_id') == $hs->id ? 'selected' : '' }}>{{ $hs->name }}</option>@endforeach</select></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>@if(request('search') || request('hostel_id'))<a href="{{ route('hostels.visitors') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-times"></i></a>@endif</div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Visitor Name','Phone','Student','Relation','Check In','Check Out','Purpose','Actions']">
            @forelse($visitors as $v)
            <tr>
                <td>{{ $v->id }}</td>
                <td class="fw-semibold">{{ $v->visitor_name }}</td>
                <td>{{ $v->visitor_phone }}</td>
                <td>{{ $v->first_name }} {{ $v->last_name }}<br><small class="text-muted">{{ $v->admission_no ?? '' }}</small></td>
                <td>{{ $v->relation ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($v->check_in)->format('d-m-Y H:i') }}</td>
                <td>{{ $v->check_out ? \Carbon\Carbon::parse($v->check_out)->format('d-m-Y H:i') : '-' }}</td>
                <td>{{ $v->purpose ?? '-' }}</td>
                <td>
                    <div class="table-actions">
                        @if(!$v->check_out)
                        <button class="btn btn-sm btn-outline-success" title="Check Out" data-bs-toggle="modal" data-bs-target="#checkoutModal{{ $v->id }}"><i class="fas fa-sign-out-alt"></i></button>
                        @endif
                        <form method="POST" action="{{ route('hostels.visitors.delete', $v->id) }}" class="d-inline" onsubmit="return confirm('Delete this visitor entry?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center py-4 text-muted">No visitor entries found.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$visitors" />

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <form method="POST" action="{{ route('hostels.visitors.store') }}">@csrf
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-1"></i>Add Visitor Entry</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Hostel</label><select name="hostel_id" class="form-select" required><option value="">Select</option>@foreach($hostels as $hs)<option value="{{ $hs->id }}">{{ $hs->name }}</option>@endforeach</select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Student</label><select name="student_id" class="form-select" required><option value="">Select</option>@foreach($students as $s)<option value="{{ $s->id }}">{{ $s->first_name }} {{ $s->last_name }} ({{ $s->admission_no }})</option>@endforeach</select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Visitor Name</label><input type="text" name="visitor_name" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Visitor Phone</label><input type="text" name="visitor_phone" class="form-control" required></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Relation</label><select name="relation" class="form-select" required><option value="">Select</option><option>Father</option><option>Mother</option><option>Guardian</option><option>Sibling</option><option>Relative</option><option>Other</option></select></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Check In</label><input type="datetime-local" name="check_in" class="form-control" value="{{ date('Y-m-d\TH:i') }}" required></div>
                    <div class="col-md-4"><label class="form-label fw-semibold">Purpose</label><input type="text" name="purpose" class="form-control" placeholder="e.g. Visit, Drop items"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Add Entry</button>
            </div>
        </form>
    </div></div>
</div>

@foreach($visitors as $v)
@if(!$v->check_out)
<div class="modal fade" id="checkoutModal{{ $v->id }}" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('hostels.visitors.update', $v->id) }}">@csrf @method('PUT')
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-sign-out-alt me-1"></i>Check Out Visitor</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <p>Visitor: <strong>{{ $v->visitor_name }}</strong> ({{ $v->visitor_phone }})</p>
                <p>Student: <strong>{{ $v->first_name }} {{ $v->last_name }}</strong></p>
                <label class="form-label fw-semibold">Check Out Time</label>
                <input type="datetime-local" name="check_out" class="form-control" value="{{ date('Y-m-d\TH:i') }}" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-check me-1"></i>Check Out</button>
            </div>
        </form>
    </div></div>
</div>
@endif
@endforeach
@endsection
