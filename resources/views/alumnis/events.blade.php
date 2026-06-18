@extends('layouts.app')
@section('title', 'Alumni Events')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-calendar me-2"></i>Alumni Events</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('alumni.index') }}">Alumni</a></li><li class="breadcrumb-item active">Events</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAlumniEventModal"><i class="fas fa-plus me-1"></i>Create Event</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Event','Date','Venue','Expected Attendees','Organizer','Status','Actions']">
            @foreach(range(1,5) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td class="fw-semibold">{{ ['Annual Alumni Meet','Homecoming 2026','Career Fair','Alumni Awards','Networking Night'][$i-1] }}</td>
                <td>Dec {{ $i }}, 2026</td>
                <td>{{ ['School Auditorium','School Grounds','Conference Hall','Banquet Hall','City Hotel'][$i-1] }}</td>
                <td>{{ rand(50,300) }}</td>
                <td>Alumni Committee</td>
                <td><span class="badge bg-{{ ['primary','warning','success'][$i%3] }}">{{ ['Upcoming','Planning','Confirmed'][$i%3] }}</span></td>
                <td><button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
<x-modal id="addAlumniEventModal" title="Create Alumni Event">
    <form>
        <x-form-input name="name" label="Event Name" required />
        <x-form-input name="date" label="Date" type="date" />
        <x-form-input name="venue" label="Venue" />
        <x-form-textarea name="description" label="Description" rows="3" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Create</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection