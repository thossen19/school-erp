@extends("layouts.app")

@section("title", "Substitution Details")

@section("content")
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Substitution Details</h1>
        <a href="{{ route('timetable.substitutions') }}" class="btn btn-outline-secondary btn-sm">Back</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <tr><th style="width:200px">Original Teacher</th><td>{{ $substitution->original_teacher_name }}</td></tr>
                <tr><th>Substitute Teacher</th><td>{{ $substitution->substitute_teacher_name }}</td></tr>
                <tr><th>Date</th><td>{{ $substitution->date }}</td></tr>
                <tr><th>Period ID</th><td>{{ $substitution->timetable_period_id }}</td></tr>
                <tr><th>Reason</th><td>{{ $substitution->reason ?? '-' }}</td></tr>
                <tr><th>Status</th>
                    <td>
                        @php
                            $badge = match($substitution->status) {
                                'approved' => 'bg-success',
                                'rejected' => 'bg-danger',
                                default => 'bg-warning text-dark',
                            };
                        @endphp
                        <span class="badge {{ $badge }}">{{ ucfirst($substitution->status) }}</span>
                    </td>
                </tr>
            </table>

            @if($substitution->status === 'pending')
                <div class="d-flex gap-2">
                    <form method="POST" action="{{ route('timetable.substitutions.approve', $substitution->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-success">Approve</button>
                    </form>
                    <form method="POST" action="{{ route('timetable.substitutions.reject', $substitution->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-danger">Reject</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection