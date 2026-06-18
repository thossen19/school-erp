@extends("layouts.app")

@section("title", "Room Details")

@section("content")
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Room Details</h1>
        <div>
            <a href="{{ route('timetable.room-allocation.edit', $room->id) }}" class="btn btn-warning btn-sm">Edit</a>
            <a href="{{ route('timetable.room-allocation') }}" class="btn btn-outline-secondary btn-sm">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <tr><th style="width:200px">Name</th><td>{{ $room->name }}</td></tr>
                <tr><th>Code</th><td><code>{{ $room->code }}</code></td></tr>
                <tr><th>Type</th><td>{{ ucfirst($room->type) }}</td></tr>
                <tr><th>Building</th><td>{{ $room->building ?? '-' }}</td></tr>
                <tr><th>Floor</th><td>{{ $room->floor ?? '-' }}</td></tr>
                <tr><th>Capacity</th><td>{{ $room->capacity }}</td></tr>
                <tr><th>Status</th><td>{!! $room->status ? '<span class="badge bg-success">Available</span>' : '<span class="badge bg-danger">Occupied</span>' !!}</td></tr>
                <tr><th>Notes</th><td>{{ $room->notes ?? '-' }}</td></tr>
            </table>
        </div>
    </div>
</div>
@endsection