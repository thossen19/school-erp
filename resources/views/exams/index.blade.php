@extends("layouts.app")

@section("title", "Exams")

@section("content")
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Exams</h1>
        <a href="{{ route('exams.create') }}" class="btn btn-primary btn-sm">+ Add New</a>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Schedules</th>
                            <th>Results</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exams as $exam)
                            <tr>
                                <td>{{ $exam->name }}</td>
                                <td>{{ $exam->examType?->name ?? '-' }}</td>
                                <td>{{ $exam->start_date?->format('d-m-Y') ?? '-' }}</td>
                                <td>{{ $exam->end_date?->format('d-m-Y') ?? '-' }}</td>
                                <td>{{ $exam->schedules_count }}</td>
                                <td>{{ $exam->results_count }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-3">No records found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <x-pagination :paginator="$exams" />
    </div>
</div>
@endsection
