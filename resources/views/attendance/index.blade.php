@extends("layouts.app")

@section("title", "Attendance")

@section("content")
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Attendance Records</h1>
        <div>
            <a href="{{ route('attendance.mark') }}" class="btn btn-primary btn-sm">Mark Attendance</a>
            <a href="{{ route('attendance.create') }}" class="btn btn-success btn-sm">+ Add New</a>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-auto">
                    <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}" placeholder="Date">
                </div>
                <div class="col-auto">
                    <select name="class_id" class="form-select form-select-sm">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
                    <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
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
                            <th>#</th>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Section</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Type</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $record)
                            <tr>
                                <td>{{ $record->id }}</td>
                                <td>{{ $record->student?->first_name }} {{ $record->student?->last_name }}</td>
                                <td>{{ $record->class?->name ?? '-' }}</td>
                                <td>{{ $record->section?->name ?? '-' }}</td>
                                <td>{{ $record->date }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($record->status) {
                                            'present' => 'bg-success',
                                            'absent' => 'bg-danger',
                                            'late' => 'bg-warning text-dark',
                                            'half_day' => 'bg-info',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ ucfirst($record->status) }}</span>
                                </td>
                                <td>{{ $record->attendance_type ?? '-' }}</td>
                                <td>{{ $record->remarks ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-3">No attendance records found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <x-pagination :paginator="$records" />
    </div>
</div>
@endsection