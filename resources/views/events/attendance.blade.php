@extends('layouts.app')
@section('title', 'Event Attendance')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-check-circle me-2"></i>Event Attendance</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('events.index') }}">Events</a></li><li class="breadcrumb-item active">Attendance</li></ol></nav>
    </div>
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
        <x-table :headers="['#','Student','Admission No','Event','Registered Date','Attended','Actions']">
            @forelse($registrations as $reg)
            <tr>
                <td>{{ $loop->iteration + ($registrations->currentPage()-1)*$registrations->perPage() }}</td>
                <td>{{ $reg->first_name ? $reg->first_name.' '.$reg->last_name : 'User #'.$reg->user_id }}</td>
                <td>{{ $reg->admission_no ?? '-' }}</td>
                <td class="fw-semibold">{{ $reg->event_title }}</td>
                <td>{{ \Carbon\Carbon::parse($reg->registration_date)->format('M d, Y') }}</td>
                <td>
                    @if($reg->attended === 'yes')
                        <span class="badge bg-success">Present</span>
                    @elseif($reg->attended === 'no')
                        <span class="badge bg-danger">Absent</span>
                    @else
                        <span class="badge bg-secondary">Not Marked</span>
                    @endif
                </td>
                <td>
                    <div class="table-actions">
                        @if($reg->attended !== 'yes')
                        <form method="POST" action="{{ route('events.attendance.mark', $reg->id) }}" class="d-inline">
                            @csrf @method('PUT')
                            <input type="hidden" name="attended" value="yes">
                            <button class="btn btn-sm btn-outline-success"><i class="fas fa-check"></i> Present</button>
                        </form>
                        @endif
                        @if($reg->attended !== 'no')
                        <form method="POST" action="{{ route('events.attendance.mark', $reg->id) }}" class="d-inline">
                            @csrf @method('PUT')
                            <input type="hidden" name="attended" value="no">
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i> Absent</button>
                        </form>
                        @endif
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
@endsection