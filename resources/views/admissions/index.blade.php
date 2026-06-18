@extends('layouts.app')
@section('title', 'Enquiries & Applications')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-door-open me-2"></i>Enquiries & Applications</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Admissions</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#applicationModal" onclick="resetAppForm()"><i class="fas fa-plus me-1"></i>New Application</button>
</div>

{{-- Enquiries --}}
<div class="card shadow-sm border-0 mb-3">
    <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
        <h6 class="fw-semibold mb-0"><i class="fas fa-question-circle me-2"></i>Enquiries</h6>
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#enquiryModal" onclick="resetEnqForm()"><i class="fas fa-plus me-1"></i>Add</button>
    </div>
    <div class="card-body p-0">
        <x-table :headers="['#','Name','Phone','Class','Source','Status','Follow Up','Actions']">
            @forelse($enquiries as $e)
            <tr>
                <td>{{ $loop->iteration + ($enquiries->currentPage()-1)*$enquiries->perPage() }}</td>
                <td class="fw-semibold">{{ $e->name }}</td>
                <td>{{ $e->phone }}</td>
                <td>{{ $e->class_id ?? '-' }}</td>
                <td>{{ $e->source ?? '-' }}</td>
                <td><span class="badge bg-{{ $e->status=='converted'?'success':($e->status=='contacted'?'warning':'primary') }}">{{ ucfirst($e->status) }}</span></td>
                <td>{{ $e->follow_up_date ? \Carbon\Carbon::parse($e->follow_up_date)->format('M d, Y') : '-' }}</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#enquiryModal" onclick='editEnquiry(@json($e))'><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('admissions.enquiries.destroy', $e->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted py-3">No enquiries</td></tr>
            @endforelse
        </x-table>
    </div>
</div>

{{-- Applications --}}
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-file-alt me-2"></i>Applications</h6></div>
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end mb-2">
            <div class="col-md-3"><input type="text" name="search" class="form-control form-control-sm" placeholder="Search name / form no..." value="{{ request('search') }}"></div>
            <div class="col-md-2"><select name="status" class="form-select form-select-sm" onchange="this.form.submit()"><option value="">All Status</option><option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option><option value="approved" {{ request('status')=='approved'?'selected':'' }}>Approved</option><option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Rejected</option><option value="admitted" {{ request('status')=='admitted'?'selected':'' }}>Admitted</option></select></div>
            <div class="col-md-2"><select name="class_id" class="form-select form-select-sm" onchange="this.form.submit()"><option value="">All Classes</option>@foreach($classes as $c)<option value="{{ $c->id }}" {{ request('class_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach</select></div>
            <div class="col-md-1"><button class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button></div>
        </form>
    </div>
    <div class="card-body p-0">
        <x-table :headers="['#','Form No','Applicant','Class','Phone','Status','Applied Date','Actions']">
            @forelse($applications as $a)
            <tr>
                <td>{{ $loop->iteration + ($applications->currentPage()-1)*$applications->perPage() }}</td>
                <td><small class="text-muted">{{ $a->form_number }}</small></td>
                <td class="fw-semibold">{{ $a->applicant_name }}</td>
                <td>{{ $a->class_name ?? '-' }}</td>
                <td>{{ $a->phone }}</td>
                <td><span class="badge bg-{{ $a->status=='admitted'?'success':($a->status=='approved'?'info':($a->status=='rejected'?'danger':'warning')) }}">{{ ucfirst($a->status) }}</span></td>
                <td>{{ \Carbon\Carbon::parse($a->applied_date)->format('M d, Y') }}</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#applicationModal" onclick='editApp(@json($a))'><i class="fas fa-edit"></i></button>
                        @if($a->status=='pending' || $a->status=='waiting')
                        <form method="POST" action="{{ route('admissions.approve', $a->id) }}" class="d-inline">@csrf<button class="btn btn-sm btn-outline-success"><i class="fas fa-check"></i></button></form>
                        @endif
                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal" data-id="{{ $a->id }}"><i class="fas fa-times"></i></button>
                        <form method="POST" action="{{ route('admissions.destroy', $a->id) }}" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted py-4">No applications found</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$applications" />

{{-- Application Modal --}}
<x-modal id="applicationModal" title="<span id='appModalTitle'>New Application</span>">
    <form method="POST" action="{{ route('admissions.store') }}" id="appForm">
        @csrf
        <input type="hidden" name="_method" id="appMethodField" value="POST">
        <input type="hidden" name="app_id" id="appId">
        <div class="row g-2">
            <div class="col-md-6"><x-form-input name="applicant_name" label="Applicant Name" required /></div>
            <div class="col-md-3"><x-form-input name="date_of_birth" label="DOB" type="date" required /></div>
            <div class="col-md-3"><x-form-select name="gender" label="Gender" :options="['male'=>'Male','female'=>'Female','other'=>'Other']" required /></div>
        </div>
        <div class="row g-2">
            <div class="col-md-4"><x-form-input name="phone" label="Phone" required /></div>
            <div class="col-md-4"><x-form-input name="email" label="Email" /></div>
            <div class="col-md-4"><x-form-select name="class_applying_for_id" label="Applying for Class" :options="$classes->pluck('name','id')->toArray()" /></div>
        </div>
        <x-form-textarea name="address" label="Address" rows="2" />
        <div class="row g-2">
            <div class="col-md-6"><x-form-input name="father_name" label="Father Name" /></div>
            <div class="col-md-6"><x-form-input name="father_phone" label="Father Phone" /></div>
        </div>
        <div class="row g-2">
            <div class="col-md-6"><x-form-input name="mother_name" label="Mother Name" /></div>
            <div class="col-md-6"><x-form-input name="mother_phone" label="Mother Phone" /></div>
        </div>
        <x-form-input name="previous_school" label="Previous School" />
        <x-form-select name="status" label="Status" :options="['pending'=>'Pending','waiting'=>'Waiting','approved'=>'Approved','rejected'=>'Rejected','admitted'=>'Admitted']" />
    </form>
    <x-slot:footer><button class="btn btn-primary" onclick="$('#appForm').submit()">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

{{-- Enquiry Modal --}}
<x-modal id="enquiryModal" title="<span id='enqModalTitle'>Add Enquiry</span>">
    <form method="POST" action="{{ route('admissions.enquiries.store') }}" id="enqForm">
        @csrf
        <input type="hidden" name="_method" id="enqMethodField" value="POST">
        <input type="hidden" name="enq_id" id="enqId">
        <div class="row g-2">
            <div class="col-md-6"><x-form-input name="name" label="Name" required /></div>
            <div class="col-md-6"><x-form-input name="phone" label="Phone" required /></div>
        </div>
        <div class="row g-2">
            <div class="col-md-4"><x-form-input name="email" label="Email" /></div>
            <div class="col-md-4"><x-form-input name="class_id" label="Class" /></div>
            <div class="col-md-4"><x-form-input name="source" label="Source" placeholder="Walk-in, Phone..." /></div>
        </div>
        <div class="row g-2">
            <div class="col-md-6"><x-form-input name="follow_up_date" label="Follow Up Date" type="date" /></div>
            <div class="col-md-6"><x-form-select name="status" label="Status" :options="['pending'=>'Pending','contacted'=>'Contacted','converted'=>'Converted','closed'=>'Closed']" /></div>
        </div>
        <x-form-textarea name="notes" label="Notes" rows="2" />
    </form>
    <x-slot:footer><button class="btn btn-primary" onclick="$('#enqForm').submit()">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

{{-- Reject Modal --}}
<x-modal id="rejectModal" title="Reject Application">
    <form method="POST" action="" id="rejectForm">
        @csrf
        <x-form-textarea name="rejection_reason" label="Rejection Reason" rows="3" required />
    </form>
    <x-slot:footer><button class="btn btn-danger" onclick="$('#rejectForm').submit()">Reject</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

@push('scripts')
<script>
function resetAppForm() {
    $('#appModalTitle').text('New Application'); $('#appForm').attr('action', '{{ route('admissions.store') }}');
    $('#appMethodField').val('POST'); $('#appForm')[0].reset(); $('#appId').val('');
}
function editApp(a) {
    $('#appModalTitle').text('Edit Application'); $('#appForm').attr('action', '{{ url('admissions/update') }}/' + a.id);
    $('#appMethodField').val('PUT'); $('#appId').val(a.id);
    $('#applicant_name').val(a.applicant_name); $('#date_of_birth').val(a.date_of_birth);
    $('#gender').val(a.gender); $('#phone').val(a.phone); $('#email').val(a.email||'');
    $('#class_applying_for_id').val(a.class_applying_for_id||''); $('#address').val(a.address||'');
    $('#father_name').val(a.father_name||''); $('#father_phone').val(a.father_phone||'');
    $('#mother_name').val(a.mother_name||''); $('#mother_phone').val(a.mother_phone||'');
    $('#previous_school').val(a.previous_school||''); $('#status').val(a.status);
}
function resetEnqForm() {
    $('#enqModalTitle').text('Add Enquiry'); $('#enqForm').attr('action', '{{ route('admissions.enquiries.store') }}');
    $('#enqMethodField').val('POST'); $('#enqForm')[0].reset(); $('#enqId').val('');
}
function editEnquiry(e) {
    $('#enqModalTitle').text('Edit Enquiry'); $('#enqForm').attr('action', '{{ url('admissions/enquiries/update') }}/' + e.id);
    $('#enqMethodField').val('PUT'); $('#enqId').val(e.id);
    $('#name').val(e.name); $('#phone').val(e.phone); $('#email').val(e.email||'');
    $('#class_id').val(e.class_id||''); $('#source').val(e.source||'');
    $('#follow_up_date').val(e.follow_up_date||''); $('#status').val(e.status); $('#notes').val(e.notes||'');
}
$('#rejectModal').on('show.bs.modal', function (e) {
    var id = $(e.relatedTarget).data('id');
    $('#rejectForm').attr('action', '{{ url('admissions/reject') }}/' + id);
});
</script>
@endpush
@endsection