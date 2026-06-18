@extends("layouts.app")

@section("title", "Substitutions")

@section("content")
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Substitution Requests</h1>
        <a href="{{ route('timetable.substitutions.create') }}" class="btn btn-primary btn-sm">+ New Request</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-auto">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-auto">
                    <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
                    <a href="{{ route('timetable.substitutions') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
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
                            <th>Original Teacher</th>
                            <th>Substitute Teacher</th>
                            <th>Date</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($substitutions as $s)
                            <tr>
                                <td>{{ $s->original_teacher_name }}</td>
                                <td>{{ $s->substitute_teacher_name }}</td>
                                <td>{{ $s->date }}</td>
                                <td>{{ Str::limit($s->reason ?? '-', 40) }}</td>
                                <td>
                                    @php
                                        $badge = match($s->status) {
                                            'approved' => 'bg-success',
                                            'rejected' => 'bg-danger',
                                            default => 'bg-warning text-dark',
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ ucfirst($s->status) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('timetable.substitutions.show', $s->id) }}" class="btn btn-sm btn-outline-info">View</a>
                                    @if($s->status === 'pending')
                                        <form method="POST" action="{{ route('timetable.substitutions.approve', $s->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('timetable.substitutions.reject', $s->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Reject</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-3">No substitution requests found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <x-pagination :paginator="$substitutions" />
    </div>
</div>
@endsection