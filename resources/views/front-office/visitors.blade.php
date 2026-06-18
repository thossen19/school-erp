@extends('layouts.app')
@section('title', 'Visitor Management')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-user-friends me-2"></i>Visitor Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Visitor Management</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#visitorModal" onclick="resetVisitorForm()"><i class="fas fa-plus me-1"></i>Add Visitor</button>
</div>

<div class="filter-bar">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-2"><label class="form-label fw-semibold small">Date From</label><input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}"></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Date To</label><input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}"></div>
        <div class="col-md-3"><label class="form-label fw-semibold small">Purpose</label><input type="text" name="purpose" class="form-control form-control-sm" placeholder="Search purpose..." value="{{ request('purpose') }}"></div>
        <div class="col-md-1"><button class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button></div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Name','Phone','Purpose','Whom to Meet','Visit Date','Check In','Check Out','Actions']">
            @forelse($visitors as $v)
            <tr>
                <td>{{ $loop->iteration + ($visitors->currentPage()-1)*$visitors->perPage() }}</td>
                <td class="fw-semibold">{{ $v->name }}</td>
                <td>{{ $v->phone }}</td>
                <td>{{ $v->purpose }}</td>
                <td>{{ $v->whom_to_meet ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($v->visit_date)->format('M d, Y') }}</td>
                <td>{{ $v->check_in ? \Carbon\Carbon::parse($v->check_in)->format('h:i A') : '-' }}</td>
                <td>{{ $v->check_out ? \Carbon\Carbon::parse($v->check_out)->format('h:i A') : '-' }}</td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#visitorModal" onclick='editVisitor(@json($v))'><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('front-office.visitors.delete', $v->id) }}" class="d-inline" onsubmit="return confirm('Delete this visitor?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center text-muted py-4">No visitors found</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$visitors" />

<x-modal id="visitorModal" title="<span id='visitorModalTitle'>Add Visitor</span>">
    <form method="POST" action="{{ route('front-office.visitors.store') }}" id="visitorForm">
        @csrf
        <input type="hidden" name="_method" id="visitorMethodField" value="POST">
        <input type="hidden" name="visitor_id" id="visitorId">
        <div class="row g-2">
            <div class="col-md-4"><x-form-input name="name" label="Name" required /></div>
            <div class="col-md-4"><x-form-input name="phone" label="Phone" required /></div>
            <div class="col-md-4"><x-form-input name="email" label="Email" /></div>
        </div>
        <x-form-input name="purpose" label="Purpose" required />
        <div class="row g-2">
            <div class="col-md-4"><x-form-input name="whom_to_meet" label="Whom to Meet" /></div>
            <div class="col-md-4"><x-form-input name="department" label="Department" /></div>
            <div class="col-md-4"><x-form-input name="id_card_number" label="ID Card Number" /></div>
        </div>
        <div class="row g-2">
            <div class="col-md-4"><x-form-input name="visit_date" label="Visit Date" type="date" required /></div>
            <div class="col-md-4"><x-form-input name="check_in" label="Check In" type="datetime-local" /></div>
            <div class="col-md-4"><x-form-input name="check_out" label="Check Out" type="datetime-local" /></div>
        </div>
        <x-form-input name="vehicle_number" label="Vehicle Number" />
        <x-form-textarea name="remarks" label="Remarks" rows="2" />
    </form>
    <x-slot:footer><button class="btn btn-primary" onclick="$('#visitorForm').submit()">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

@push('scripts')
<script>
function resetVisitorForm() {
    $('#visitorModalTitle').text('Add Visitor');
    $('#visitorForm').attr('action', '{{ route('front-office.visitors.store') }}');
    $('#visitorMethodField').val('POST');
    $('#visitorForm')[0].reset();
    $('#visitorId').val('');
}
function editVisitor(v) {
    $('#visitorModalTitle').text('Edit Visitor');
    $('#visitorForm').attr('action', '{{ url('front-office/visitors/update') }}/' + v.id);
    $('#visitorMethodField').val('PUT');
    $('#visitorId').val(v.id);
    $('#name').val(v.name);
    $('#phone').val(v.phone);
    $('#email').val(v.email||'');
    $('#purpose').val(v.purpose);
    $('#whom_to_meet').val(v.whom_to_meet||'');
    $('#department').val(v.department||'');
    $('#id_card_number').val(v.id_card_number||'');
    $('#visit_date').val(v.visit_date);
    $('#check_in').val(v.check_in ? v.check_in.replace(' ','T') : '');
    $('#check_out').val(v.check_out ? v.check_out.replace(' ','T') : '');
    $('#vehicle_number').val(v.vehicle_number||'');
    $('#remarks').val(v.remarks||'');
}
</script>
@endpush
@endsection