@extends('layouts.app')
@section('title', 'Assessments')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-clipboard-check me-2"></i>Assessments</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Assessments</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('assessment.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>New Exam</a>
        <a href="{{ route('assessment.results') }}" class="btn btn-outline-success"><i class="fas fa-poll me-1"></i>Results</a>
        <a href="{{ route('assessment.grading') }}" class="btn btn-outline-info"><i class="fas fa-layer-group me-1"></i>Grading</a>
    </div>
</div>

<div class="filter-bar">
    <form method="GET" action="{{ route('assessment.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Status</label>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Exam Name','Type','Start Date','End Date','Schedules','Results','Actions']">
            @forelse($exams as $exam)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><a href="{{ route('assessment.show', $exam->id) }}" class="text-decoration-none fw-semibold">{{ $exam->name }}</a></td>
                <td>{{ $exam->examType?->name ?? '-' }}</td>
                <td>{{ $exam->start_date?->format('d-m-Y') ?? '-' }}</td>
                <td>{{ $exam->end_date?->format('d-m-Y') ?? '-' }}</td>
                <td>{{ $exam->schedules_count }}</td>
                <td>{{ $exam->results_count }}</td>
                <td>
                    <div class="table-actions">
                        <a href="{{ route('assessment.show', $exam->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('assessment.edit', $exam->id) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('assessment.destroy', $exam->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this exam?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted py-3">No exams found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$exams" />
</div>
@endsection
