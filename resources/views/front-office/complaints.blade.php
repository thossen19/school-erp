@extends('layouts.app')
@section('title', 'Complaint Management')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-exclamation-triangle me-2"></i>Complaint Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Complaint Management</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#complaintModal" onclick="resetComplaintForm()"><i class="fas fa-plus me-1"></i>Register Complaint</button>
</div>

<div class="filter-bar">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3"><label class="form-label fw-semibold small">Type</label>
            <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Types</option><option value="infrastructure" {{ request('type')=='infrastructure'?'selected':'' }}>Infrastructure</option>
                <option value="academic" {{ request('type')=='academic'?'selected':'' }}>Academic</option>
                <option value="administrative" {{ request('type')=='administrative'?'selected':'' }}>Administrative</option>
                <option value="harassment" {{ request('type')=='harassment'?'selected':'' }}>Harassment</option>
                <option value="other" {{ request('type')=='other'?'selected':'' }}>Other</option>
            </select>
        </div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Priority</label>
            <select name="priority" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All</option><option value="low" {{ request('priority')=='low'?'selected':'' }}>Low</option>
                <option value="medium" {{ request('priority')=='medium'?'selected':'' }}>Medium</option>
                <option value="high" {{ request('priority')=='high'?'selected':'' }}>High</option>
                <option value="critical" {{ request('priority')=='critical'?'selected':'' }}>Critical</option>
            </select>
        </div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Status</label>
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All</option><option value="open" {{ request('status')=='open'?'selected':'' }}>Open</option>
                <option value="in_progress" {{ request('status')=='in_progress'?'selected':'' }}>In Progress</option>
                <option value="resolved" {{ request('status')=='resolved'?'selected':'' }}>Resolved</option>
                <option value="closed" {{ request('status')=='closed'?'selected':'' }}>Closed</option>
            </select>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Complainant','Phone','Type','Priority','Assigned To','Status','Actions']">
            @forelse($complaints as $c)
            <tr>
                <td>{{ $loop->iteration + ($complaints->currentPage()-1)*$complaints->perPage() }}</td>
                <td class="fw-semibold">{{ $c->complainant_name }}</td>
                <td>{{ $c->complainant_phone }}</td>
                <td>{{ $c->complaint_type }}</td>
                <td><span class="badge bg-{{ $c->priority=='critical'?'danger':($c->priority=='high'?'warning':($c->priority=='medium'?'info':'secondary')) }}">{{ ucfirst($c->priority) }}</span></td>
                <td>{{ $c->assigned_to ?? 'Unassigned' }}</td>
                <td><span class="badge bg-{{ $c->status=='closed'?'secondary':($c->status=='resolved'?'success':($c->status=='in_progress'?'warning':'danger')) }}">{{ str_replace('_',' ',ucfirst($c->status)) }}</span></td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#complaintModal" onclick='editComplaint(@json($c))'><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('front-office.complaints.delete', $c->id) }}" class="d-inline" onsubmit="return confirm('Delete this complaint?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted py-4">No complaints found</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$complaints" />

<x-modal id="complaintModal" title="<span id='complaintModalTitle'>Register Complaint</span>">
    <form method="POST" action="{{ route('front-office.complaints.store') }}" id="complaintForm">
        @csrf
        <input type="hidden" name="_method" id="complaintMethodField" value="POST">
        <input type="hidden" name="complaint_id" id="complaintId">
        <div class="row g-2">
            <div class="col-md-4"><x-form-input name="complainant_name" label="Name" required /></div>
            <div class="col-md-4"><x-form-input name="complainant_phone" label="Phone" required /></div>
            <div class="col-md-4"><x-form-input name="complainant_email" label="Email" /></div>
        </div>
        <div class="row g-2">
            <div class="col-md-6"><x-form-input name="complaint_type" label="Complaint Type" required placeholder="e.g. Infrastructure, Academic..." /></div>
            <div class="col-md-3"><x-form-select name="priority" label="Priority" :options="['low'=>'Low','medium'=>'Medium','high'=>'High','critical'=>'Critical']" required /></div>
            <div class="col-md-3"><x-form-select name="status" label="Status" :options="['open'=>'Open','in_progress'=>'In Progress','resolved'=>'Resolved','closed'=>'Closed']" /></div>
        </div>
        <x-form-textarea name="description" label="Description" rows="3" required />
        <x-form-input name="assigned_to" label="Assigned To" />
        <x-form-textarea name="resolution" label="Resolution" rows="2" />
        <x-form-input name="resolution_notes" label="Resolution Notes" />
    </form>
    <x-slot:footer><button class="btn btn-primary" onclick="$('#complaintForm').submit()">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

@push('scripts')
<script>
function resetComplaintForm() {
    $('#complaintModalTitle').text('Register Complaint');
    $('#complaintForm').attr('action', '{{ route('front-office.complaints.store') }}');
    $('#complaintMethodField').val('POST');
    $('#complaintForm')[0].reset();
    $('#complaintId').val('');
}
function editComplaint(c) {
    $('#complaintModalTitle').text('Edit Complaint');
    $('#complaintForm').attr('action', '{{ url('front-office/complaints/update') }}/' + c.id);
    $('#complaintMethodField').val('PUT');
    $('#complaintId').val(c.id);
    $('#complainant_name').val(c.complainant_name);
    $('#complainant_phone').val(c.complainant_phone);
    $('#complainant_email').val(c.complainant_email||'');
    $('#complaint_type').val(c.complaint_type);
    $('#priority').val(c.priority);
    $('#status').val(c.status);
    $('#description').val(c.description);
    $('#assigned_to').val(c.assigned_to||'');
    $('#resolution').val(c.resolution||'');
    $('#resolution_notes').val(c.resolution_notes||'');
}
</script>
@endpush
@endsection