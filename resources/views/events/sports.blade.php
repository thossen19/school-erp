@extends('layouts.app')
@section('title', 'Sports Activities')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-running me-2"></i>Sports Activities</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('events.index') }}">Events</a></li><li class="breadcrumb-item active">Sports Activities</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sportsModal"><i class="fas fa-plus me-1"></i>Add Sports Activity</button>
</div>

<div class="filter-bar">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-2"><label class="form-label fw-semibold small">Status</label>
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All</option><option value="published" {{ request('status')=='published'?'selected':'' }}>Published</option>
                <option value="upcoming" {{ request('status')=='upcoming'?'selected':'' }}>Upcoming</option>
                <option value="ongoing" {{ request('status')=='ongoing'?'selected':'' }}>Ongoing</option>
                <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Completed</option>
            </select>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Activity','Start Date','End Date','Venue','Organizer','Status','Actions']">
            @forelse($events as $event)
            <tr>
                <td>{{ $loop->iteration + ($events->currentPage()-1)*$events->perPage() }}</td>
                <td class="fw-semibold">{{ $event->title }}</td>
                <td>{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</td>
                <td>{{ $event->end_date ? \Carbon\Carbon::parse($event->end_date)->format('M d, Y') : '-' }}</td>
                <td>{{ $event->venue ?? '-' }}</td>
                <td>{{ $event->organizer ?? '-' }}</td>
                <td><span class="badge bg-{{ $event->status=='completed'?'success':($event->status=='ongoing'?'warning':($event->status=='cancelled'?'danger':'primary')) }}">{{ ucfirst($event->status) }}</span></td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#sportsModal" onclick='editSports(@json($event))'><i class="fas fa-edit"></i></button>
                        <form method="POST" action="{{ route('events.destroy', $event->id) }}" class="d-inline" onsubmit="return confirm('Delete this activity?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted py-4">No sports activities found</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$events" />

<x-modal id="sportsModal" title="<span id='sportsModalTitle'>Add Sports Activity</span>">
    <form method="POST" action="{{ route('events.store') }}" id="sportsForm">
        @csrf
        <input type="hidden" name="_method" id="sportsMethodField" value="POST">
        <input type="hidden" name="event_type" value="sports">
        <input type="hidden" name="status" value="published">
        <div class="row g-2">
            <div class="col-md-8"><x-form-input name="title" label="Activity Name" required /></div>
            <div class="col-md-4"><x-form-input name="organizer" label="Coach/Official" /></div>
        </div>
        <x-form-textarea name="description" label="Description" rows="2" />
        <div class="row g-2">
            <div class="col-md-4"><x-form-input name="start_date" label="Start Date" type="date" required /></div>
            <div class="col-md-4"><x-form-input name="end_date" label="End Date" type="date" /></div>
            <div class="col-md-4"><x-form-input name="venue" label="Venue" /></div>
        </div>
    </form>
    <x-slot:footer><button class="btn btn-primary" onclick="$('#sportsForm').submit()">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>

@push('scripts')
<script>
function editSports(event) {
    $('#sportsModalTitle').text('Edit Sports Activity');
    $('#sportsForm').attr('action', '{{ url('events/update') }}/' + event.id);
    $('#sportsMethodField').val('PUT');
    $('#title').val(event.title);
    $('#description').val(event.description||'');
    $('#start_date').val(event.start_date);
    $('#end_date').val(event.end_date||'');
    $('#venue').val(event.venue||'');
    $('#organizer').val(event.organizer||'');
}
</script>
@endpush
@endsection