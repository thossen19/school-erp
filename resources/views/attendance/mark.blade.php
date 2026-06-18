@extends("layouts.app")

@section("title", "Mark Attendance")

@section("content")
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Mark Attendance</h1>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('attendance.mark') }}" class="row g-2">
                <div class="col-md-4">
                    <label class="form-label">Class</label>
                    <select name="class_id" class="form-select" required>
                        <option value="">Select Class</option>
                        @foreach($classes ?? [] as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Load Students</button>
                </div>
            </form>
        </div>
    </div>

    @if(isset($students) && count($students) > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Mark Attendance for {{ request('date') }}</h5>
            </div>
            <div class="card-body p-0">
                <form method="POST" action="{{ route('attendance.store') }}">
                    @csrf
                    <input type="hidden" name="date" value="{{ request('date') }}">
                    <input type="hidden" name="class_id" value="{{ request('class_id') }}">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Section</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    <tr>
                                        <td>{{ $student->id }}</td>
                                        <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                        <td>{{ $student->class?->name ?? '-' }}</td>
                                        <td>{{ $student->section?->name ?? '-' }}</td>
                                        <td>
                                            @php $existing = $existingAttendances[$student->id] ?? null; @endphp
                                            <input type="hidden" name="records[{{ $loop->index }}][student_id]" value="{{ $student->id }}">
                                            <select name="records[{{ $loop->index }}][status]" class="form-select form-select-sm">
                                                <option value="present" {{ $existing && $existing->status == 'present' ? 'selected' : '' }}>Present</option>
                                                <option value="absent" {{ $existing && $existing->status == 'absent' ? 'selected' : '' }}>Absent</option>
                                                <option value="late" {{ $existing && $existing->status == 'late' ? 'selected' : '' }}>Late</option>
                                                <option value="half_day" {{ $existing && $existing->status == 'half_day' ? 'selected' : '' }}>Half Day</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">Save Attendance</button>
                    </div>
                </form>
            </div>
        </div>
    @elseif(request()->has('class_id'))
        <div class="alert alert-info">No students found for the selected class.</div>
    @endif
</div>
@endsection