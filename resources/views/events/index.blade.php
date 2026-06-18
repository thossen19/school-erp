@extends('layouts.app')
@section('title', 'School Events')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-calendar-alt me-2"></i>School Events</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">School Events</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#eventModal" onclick="resetEventForm()"><i class="fas fa-plus me-1"></i>Add Event</button>
</div>

<div class="filter-bar">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3"><label class="form-label fw-semibold small">Type</label>
            <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Types</option><option value="academic" {{ request('type')=='academic'?'selected':'' }}>Academic</option>
                <option value="sports" {{ request('type')=='sports'?'selected':'' }}>Sports</option>
                <option value="cultural" {{ request('type')=='cultural'?'selected':'' }}>Cultural</option>
                <option value="competition" {{ request('type')=='competition'?'selected':'' }}>Competition</option>
                <option value="workshop" {{ request('type')=='workshop'?'selected':'' }}>Workshop</option>
                <option value="holiday" {{ request('type')=='holiday'?'selected':'' }}>Holiday</option>
                <option value="meeting" {{ request('type')=='meeting'?'selected':'' }}>Meeting</option>
                <option value="other" {{ request('type')=='other'?'selected':'' }}>Other</option>
            </select>
        </div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Status</label>
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Status</option><option value="draft" {{ request('status')=='draft'?'selected':'' }}>Draft</option>
                <option value="published" {{ request('status')=='published'?'selected':'' }}>Published</option>
                <option value="upcoming" {{ request('status')=='upcoming'?'selected':'' }}>Upcoming</option>
                <option value="ongoing" {{ request('status')=='ongoing'?'selected':'' }}>Ongoing</option>
                <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Completed</option>
                <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Cancelled</option>
            </select>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Event Name','Type','Start Date','End Date','Venue','Registrations','Status','Actions']">
            @forelse($events as $event)
            <tr>
                <td>{{ $loop->iteration + ($events->currentPage()-1)*$events->perPage() }}</td>
                <td class="fw-semibold">{{ $event->title }}</td>
                <td><span class="badge bg-info">{{ ucfirst($event->event_type) }}</span></td>
                <td>{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</td>
                <td>{{ $event->end_date ? \Carbon\Carbon::parse($event->end_date)->format('M d, Y') : '-' }}</td>
                <td>{{ $event->venue ?? '-' }}</td>
                <td>{{ $registrationCounts[$event->id] ?? 0 }}</td>
                <td><span class="badge bg-{{ $event->status=='completed'?'success':($event->status=='ongoing'?'warning':($event->status=='cancelled'?'danger':'primary')) }}">{{ ucfirst($event->status) }}</span></td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#eventModal" onclick='editEvent(@json($event))'><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('events.destroy', $event->id) }}" class="d-inline" onsubmit="return confirm('Delete this event?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center text-muted py-4">No events found</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$events" />

<x-modal id="eventModal" title="<span id='modalTitle'>Add Event</span>">
    <form method="POST" action="{{ route('events.store') }}" id="eventForm">
        @csrf
        <input type="hidden" name="_method" id="methodField" value="POST">
        <input type="hidden" name="event_id" id="eventId">
        <div class="row g-2">
            <div class="col-md-8"><x-form-input name="title" label="Event Title" required /></div>
            <div class="col-md-4"><x-form-select name="event_type" label="Type" :options="['academic'=>'Academic','sports'=>'Sports','cultural'=>'Cultural','competition'=>'Competition','workshop'=>'Workshop','holiday'=>'Holiday','meeting'=>'Meeting','other'=>'Other']" required /></div>
        </div>
        <x-form-textarea name="description" label="Description" rows="2" />
        <div class="row g-2">
            <div class="col-md-4"><x-form-input name="start_date" label="Start Date" type="date" required /></div>
            <div class="col-md-4"><x-form-input name="end_date" label="End Date" type="date" /></div>
            <div class="col-md-4"><x-form-input name="venue" label="Venue" /></div>
        </div>
        <div class="row g-2">
            <div class="col-md-4"><x-form-input name="organizer" label="Organizer" /></div>
            <div class="col-md-4"><x-form-input name="max_participants" label="Max Participants" type="number" /></div>
            <div class="col-md-2"><x-form-input name="fee" label="Fee" type="number" step="0.01" value="0" /></div>
            <div class="col-md-2"><div class="form-check mt-4"><input type="checkbox" class="form-check-input" name="registration_required" id="regRequired" value="1"><label class="form-check-label small" for="regRequired">Registration Required</label></div></div>
        </div>
        <x-form-select name="status" label="Status" :options="['draft'=>'Draft','published'=>'Published','upcoming'=>'Upcoming','ongoing'=>'Ongoing','completed'=>'Completed','cancelled'=>'Cancelled']" required />
    </form>
    <x-slot:footer><button class="btn btn-primary" onclick="$('#eventForm').submit()">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

@push('scripts')
<script>
function resetEventForm() {
    $('#modalTitle').text('Add Event');
    $('#eventForm').attr('action', '{{ route('events.store') }}');
    $('#methodField').val('POST');
    $('#eventForm')[0].reset();
    $('#eventId').val('');
}
function editEvent(event) {
    $('#modalTitle').text('Edit Event');
    $('#eventForm').attr('action', '{{ url('events/update') }}/' + event.id);
    $('#methodField').val('PUT');
    $('#eventId').val(event.id);
    $('#title').val(event.title);
    $('#event_type').val(event.event_type);
    $('#description').val(event.description||'');
    $('#start_date').val(event.start_date);
    $('#end_date').val(event.end_date||'');
    $('#venue').val(event.venue||'');
    $('#organizer').val(event.organizer||'');
    $('#max_participants').val(event.max_participants||'');
    $('#fee').val(event.fee||0);
    $('#regRequired').prop('checked', event.registration_required==1);
    $('#status').val(event.status);
}
</script>
@endpush
@endsection