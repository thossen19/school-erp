@extends('layouts.app')
@section('title', 'Enquiry Management')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-question-circle me-2"></i>Enquiry Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Enquiry Management</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#enquiryModal" onclick="resetEnquiryForm()"><i class="fas fa-plus me-1"></i>Add Enquiry</button>
</div>

<div class="filter-bar">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3"><label class="form-label fw-semibold small">Type</label>
            <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Types</option><option value="admission" {{ request('type')=='admission'?'selected':'' }}>Admission</option>
                <option value="academic" {{ request('type')=='academic'?'selected':'' }}>Academic</option>
                <option value="fee" {{ request('type')=='fee'?'selected':'' }}>Fee</option>
                <option value="transport" {{ request('type')=='transport'?'selected':'' }}>Transport</option>
                <option value="other" {{ request('type')=='other'?'selected':'' }}>Other</option>
            </select>
        </div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Status</label>
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All</option><option value="open" {{ request('status')=='open'?'selected':'' }}>Open</option>
                <option value="in_progress" {{ request('status')=='in_progress'?'selected':'' }}>In Progress</option>
                <option value="closed" {{ request('status')=='closed'?'selected':'' }}>Closed</option>
            </select>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Enquirer','Phone','Type','Assigned To','Follow Up','Status','Actions']">
            @forelse($enquiries as $e)
            <tr>
                <td>{{ $loop->iteration + ($enquiries->currentPage()-1)*$enquiries->perPage() }}</td>
                <td class="fw-semibold">{{ $e->enquirer_name }}</td>
                <td>{{ $e->enquirer_phone }}</td>
                <td>{{ $e->enquiry_type }}</td>
                <td>{{ $e->assigned_to ?? 'Unassigned' }}</td>
                <td>{{ $e->follow_up_date ? \Carbon\Carbon::parse($e->follow_up_date)->format('M d, Y') : '-' }}</td>
                <td><span class="badge bg-{{ $e->status=='closed'?'success':($e->status=='in_progress'?'warning':'primary') }}">{{ str_replace('_',' ',ucfirst($e->status)) }}</span></td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#enquiryModal" onclick='editEnquiry(@json($e))'><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('front-office.enquiries.delete', $e->id) }}" class="d-inline" onsubmit="return confirm('Delete this enquiry?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted py-4">No enquiries found</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$enquiries" />

<x-modal id="enquiryModal" title="<span id='enquiryModalTitle'>Add Enquiry</span>">
    <form method="POST" action="{{ route('front-office.enquiries.store') }}" id="enquiryForm">
        @csrf
        <input type="hidden" name="_method" id="enquiryMethodField" value="POST">
        <input type="hidden" name="enquiry_id" id="enquiryId">
        <div class="row g-2">
            <div class="col-md-4"><x-form-input name="enquirer_name" label="Name" required /></div>
            <div class="col-md-4"><x-form-input name="enquirer_phone" label="Phone" required /></div>
            <div class="col-md-4"><x-form-input name="enquirer_email" label="Email" /></div>
        </div>
        <div class="row g-2">
            <div class="col-md-4"><x-form-select name="enquiry_type" label="Enquiry Type" :options="['admission'=>'Admission','academic'=>'Academic','fee'=>'Fee','transport'=>'Transport','general'=>'General','other'=>'Other']" required /></div>
            <div class="col-md-4"><x-form-input name="source" label="Source" placeholder="Walk-in, Phone, Email..." /></div>
            <div class="col-md-4"><x-form-input name="assigned_to" label="Assigned To" /></div>
        </div>
        <x-form-textarea name="message" label="Message" rows="2" />
        <div class="row g-2">
            <div class="col-md-4"><x-form-input name="follow_up_date" label="Follow Up Date" type="date" /></div>
            <div class="col-md-8"><x-form-select name="status" label="Status" :options="['open'=>'Open','in_progress'=>'In Progress','closed'=>'Closed']" /></div>
        </div>
        <x-form-textarea name="response" label="Response" rows="2" />
    </form>
    <x-slot:footer><button class="btn btn-primary" onclick="$('#enquiryForm').submit()">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

@push('scripts')
<script>
function resetEnquiryForm() {
    $('#enquiryModalTitle').text('Add Enquiry');
    $('#enquiryForm').attr('action', '{{ route('front-office.enquiries.store') }}');
    $('#enquiryMethodField').val('POST');
    $('#enquiryForm')[0].reset();
    $('#enquiryId').val('');
    $('#status').val('open');
}
function editEnquiry(e) {
    $('#enquiryModalTitle').text('Edit Enquiry');
    $('#enquiryForm').attr('action', '{{ url('front-office/enquiries/update') }}/' + e.id);
    $('#enquiryMethodField').val('PUT');
    $('#enquiryId').val(e.id);
    $('#enquirer_name').val(e.enquirer_name);
    $('#enquirer_phone').val(e.enquirer_phone);
    $('#enquirer_email').val(e.enquirer_email||'');
    $('#enquiry_type').val(e.enquiry_type);
    $('#source').val(e.source||'');
    $('#assigned_to').val(e.assigned_to||'');
    $('#message').val(e.message||'');
    $('#follow_up_date').val(e.follow_up_date||'');
    $('#status').val(e.status);
    $('#response').val(e.response||'');
}
</script>
@endpush
@endsection