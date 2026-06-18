@extends('layouts.app')
@section('title', 'Appointment Scheduling')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-calendar-alt me-2"></i>Appointment Scheduling</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Appointment Scheduling</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#appointmentModal" onclick="resetAppointmentForm()"><i class="fas fa-plus me-1"></i>Schedule Appointment</button>
</div>

<div class="filter-bar">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-2"><label class="form-label fw-semibold small">Date</label><input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}" onchange="this.form.submit()"></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Status</label>
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All</option><option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                <option value="confirmed" {{ request('status')=='confirmed'?'selected':'' }}>Confirmed</option>
                <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Completed</option>
                <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Cancelled</option>
            </select>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Visitor','Phone','Staff','Date','Time','Purpose','Status','Actions']">
            @forelse($appointments as $a)
            <tr>
                <td>{{ $loop->iteration + ($appointments->currentPage()-1)*$appointments->perPage() }}</td>
                <td class="fw-semibold">{{ $a->visitor_name }}</td>
                <td>{{ $a->visitor_phone }}</td>
                <td>{{ $a->staff_name ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($a->date)->format('M d, Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($a->time)->format('h:i A') }}</td>
                <td>{{ \Str::limit($a->purpose, 25) }}</td>
                <td><span class="badge bg-{{ $a->status=='confirmed'?'success':($a->status=='completed'?'secondary':($a->status=='cancelled'?'danger':'warning')) }}">{{ ucfirst($a->status) }}</span></td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#appointmentModal" onclick='editAppointment(@json($a))'><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('front-office.appointments.delete', $a->id) }}" class="d-inline" onsubmit="return confirm('Delete this appointment?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center text-muted py-4">No appointments found</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$appointments" />

<x-modal id="appointmentModal" title="<span id='apptModalTitle'>Schedule Appointment</span>">
    <form method="POST" action="{{ route('front-office.appointments.store') }}" id="appointmentForm">
        @csrf
        <input type="hidden" name="_method" id="apptMethodField" value="POST">
        <input type="hidden" name="appointment_id" id="appointmentId">
        <div class="row g-2">
            <div class="col-md-6"><x-form-input name="visitor_name" label="Visitor Name" required /></div>
            <div class="col-md-3"><x-form-input name="visitor_phone" label="Phone" required /></div>
            <div class="col-md-3"><x-form-input name="visitor_email" label="Email" /></div>
        </div>
        <div class="row g-2">
            <div class="col-md-4"><x-form-input name="date" label="Date" type="date" required /></div>
            <div class="col-md-4"><x-form-input name="time" label="Time" type="time" required /></div>
            <div class="col-md-4"><x-form-input name="end_time" label="End Time" type="time" /></div>
        </div>
        <x-form-input name="purpose" label="Purpose" required />
        <x-form-select name="staff_id" label="Staff Member" :options="[]" placeholder="Enter staff ID manually" />
        <x-form-select name="status" label="Status" :options="['pending'=>'Pending','confirmed'=>'Confirmed','completed'=>'Completed','cancelled'=>'Cancelled']" />
    </form>
    <x-slot:footer><button class="btn btn-primary" onclick="$('#appointmentForm').submit()">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

@push('scripts')
<script>
function resetAppointmentForm() {
    $('#apptModalTitle').text('Schedule Appointment');
    $('#appointmentForm').attr('action', '{{ route('front-office.appointments.store') }}');
    $('#apptMethodField').val('POST');
    $('#appointmentForm')[0].reset();
    $('#appointmentId').val('');
}
function editAppointment(a) {
    $('#apptModalTitle').text('Edit Appointment');
    $('#appointmentForm').attr('action', '{{ url('front-office/appointments/update') }}/' + a.id);
    $('#apptMethodField').val('PUT');
    $('#appointmentId').val(a.id);
    $('#visitor_name').val(a.visitor_name);
    $('#visitor_phone').val(a.visitor_phone);
    $('#visitor_email').val(a.visitor_email||'');
    $('#date').val(a.date);
    $('#time').val(a.time);
    $('#end_time').val(a.end_time||'');
    $('#purpose').val(a.purpose);
    $('#staff_id').val(a.staff_id||'');
    $('#status').val(a.status);
}
</script>
@endpush
@endsection