@extends("layouts.app")

@section("title", "Room Allocations")

@section("content")
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Room Allocations</h1>
        <a href="{{ route('timetable.room-allocation.create') }}" class="btn btn-primary btn-sm">+ Add Room</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-auto">
                    <select name="type" class="form-select form-select-sm">
                        <option value="">All Types</option>
                        <option value="classroom" {{ request('type') == 'classroom' ? 'selected' : '' }}>Classroom</option>
                        <option value="laboratory" {{ request('type') == 'laboratory' ? 'selected' : '' }}>Laboratory</option>
                        <option value="library" {{ request('type') == 'library' ? 'selected' : '' }}>Library</option>
                        <option value="office" {{ request('type') == 'office' ? 'selected' : '' }}>Office</option>
                        <option value="auditorium" {{ request('type') == 'auditorium' ? 'selected' : '' }}>Auditorium</option>
                        <option value="sports" {{ request('type') == 'sports' ? 'selected' : '' }}>Sports</option>
                    </select>
                </div>
                <div class="col-auto">
                    <input type="text" name="search" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="Search rooms...">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
                    <a href="{{ route('timetable.room-allocation') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Type</th>
                            <th>Building</th>
                            <th>Floor</th>
                            <th>Capacity</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rooms as $room)
                            <tr>
                                <td>{{ $room->name }}</td>
                                <td><code>{{ $room->code }}</code></td>
                                <td>{{ ucfirst($room->type) }}</td>
                                <td>{{ $room->building ?? '-' }}</td>
                                <td>{{ $room->floor ?? '-' }}</td>
                                <td>{{ $room->capacity }}</td>
                                <td>
                                    @if($room->status)
                                        <span class="badge bg-success">Available</span>
                                    @else
                                        <span class="badge bg-danger">Occupied</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-3">No rooms found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <x-pagination :paginator="$rooms" />
    </div>
</div>
@endsection