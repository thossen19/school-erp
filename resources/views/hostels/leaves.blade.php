@extends('layouts.app')
@section('title', 'Leave Management')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-calendar-times me-2"></i>Leave Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Hostel</li><li class="breadcrumb-item active">Leave Management</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>Add Leave</button>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label fw-semibold small">Search</label><input type="text" name="search" class="form-control form-control-sm" placeholder="Student name or admission no..." value="{{ request('search') }}"></div>
            <div class="col-md-2"><label class="form-label fw-semibold small">Status</label><select name="status" class="form-select form-select-sm"><option value="">All</option><option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option><option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option><option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option></select></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>@if(request('search') || request('status'))<a href="{{ route('hostels.leaves') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-times"></i></a>@endif</div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Student','Hostel','From','To','Reason','Parent','Warden','Status','Actions']">
            @forelse($leaves as $l)
            <tr>
                <td>{{ $l->id }}</td>
                <td class="fw-semibold">{{ $l->first_name }} {{ $l->last_name }}<br><small class="text-muted">{{ $l->admission_no }}</small></td>
                <td>{{ $l->hostel_name }}</td>
                <td>{{ $l->from_date }}</td>
                <td>{{ $l->to_date }}</td>
                <td><small>{{ Str::limit($l->reason, 30) }}</small></td>
                <td>@if($l->parent_approval)<span class="badge bg-success">Yes</span>@else<span class="badge bg-secondary">No</span>@endif</td>
                <td>@if($l->warden_approval)<span class="badge bg-success">Yes</span>@else<span class="badge bg-secondary">No</span>@endif</td>
                <td>@php $b = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger']; @endphp<span class="badge bg-{{ $b[$l->status] ?? 'secondary' }}">{{ ucfirst($l->status) }}</span></td>
                <td>
                    <div class="table-actions">
                        @if($l->status === 'pending')
                        <form method="POST" action="{{ route('hostels.leaves.update', $l->id) }}" class="d-inline">@csrf @method('PUT')<input type="hidden" name="status" value="approved"><button class="btn btn-sm btn-outline-success" title="Approve"><i class="fas fa-check"></i></button></form>
                        <form method="POST" action="{{ route('hostels.leaves.update', $l->id) }}" class="d-inline">@csrf @method('PUT')<input type="hidden" name="status" value="rejected"><button class="btn btn-sm btn-outline-danger" title="Reject"><i class="fas fa-times"></i></button></form>
                        @endif
                        <form method="POST" action="{{ route('hostels.leaves.delete', $l->id) }}" class="d-inline" onsubmit="return confirm('Delete this leave request?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-secondary" title="Delete"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="10" class="text-center py-4 text-muted">No leave requests found.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$leaves" />

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('hostels.leaves.store') }}">@csrf
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-1"></i>Add Leave Request</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Hostel</label><select name="hostel_id" class="form-select" required><option value="">Select</option>@foreach($hostels as $hs)<option value="{{ $hs->id }}">{{ $hs->name }}</option>@endforeach</select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Student</label><select name="student_id" class="form-select" required><option value="">Select</option>@foreach($students as $s)<option value="{{ $s->id }}">{{ $s->first_name }} {{ $s->last_name }} ({{ $s->admission_no }})</option>@endforeach</select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">From Date</label><input type="date" name="from_date" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">To Date</label><input type="date" name="to_date" class="form-control" required></div>
                    <div class="col-12"><label class="form-label fw-semibold">Reason</label><textarea name="reason" class="form-control" rows="3" required></textarea></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Submit</button>
            </div>
        </form>
    </div></div>
</div>
@endsection
