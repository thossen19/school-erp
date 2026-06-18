@extends('layouts.app')
@section('title', 'Event Registration')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-clipboard-list me-2"></i>Event Registration</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('events.index') }}">Events</a></li><li class="breadcrumb-item active">Registration</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registrationModal"><i class="fas fa-plus me-1"></i>New Registration</button>
</div>

<div class="filter-bar">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3"><label class="form-label fw-semibold small">Event</label>
            <select name="event_id" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Events</option>
                @foreach($events as $ev)
                    <option value="{{ $ev->id }}" {{ $eventId==$ev->id ? 'selected' : '' }}>{{ $ev->title }}</option>
                @endforeach
            </select>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Student','Admission No','Event','Registration Date','Status','Actions']">
            @forelse($registrations as $reg)
            <tr>
                <td>{{ $loop->iteration + ($registrations->currentPage()-1)*$registrations->perPage() }}</td>
                <td>{{ $reg->first_name ? $reg->first_name.' '.$reg->last_name : 'User #'.$reg->user_id }}</td>
                <td>{{ $reg->admission_no ?? '-' }}</td>
                <td class="fw-semibold">{{ $reg->event_title }}</td>
                <td>{{ \Carbon\Carbon::parse($reg->registration_date)->format('M d, Y') }}</td>
                <td><span class="badge bg-{{ $reg->status=='registered'?'success':($reg->status=='cancelled'?'danger':'warning') }}">{{ ucfirst($reg->status) }}</span></td>
                <td>
                    <div class="table-actions">
                        <form method="POST" action="{{ route('events.registration.destroy', $reg->id) }}" class="d-inline" onsubmit="return confirm('Cancel this registration?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i></button></form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center text-muted py-4">No registrations found</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$registrations" />

<x-modal id="registrationModal" title="New Registration">
    <form method="POST" action="{{ route('events.registration.store') }}">
        @csrf
        <x-form-select name="event_id" label="Event" :options="$events->pluck('title','id')->toArray()" required />
        <x-form-input name="student_id" label="Student ID" type="number" required placeholder="Enter student ID" />
    </form>
    <x-slot:footer><button class="btn btn-primary" onclick="$(this).closest('.modal').find('form').submit()">Register</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection