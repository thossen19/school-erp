@extends('layouts.app')
@section('title', 'Assessment Detail')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-clipboard-list me-2"></i>Assessment Detail</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('assessment.index') }}">Assessments</a></li><li class="breadcrumb-item active">{{ $exam->name }}</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('assessment.edit', $exam->id) }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i>Edit</a>
        <button class="btn btn-success"><i class="fas fa-check me-1"></i>Publish Results</button>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="fw-semibold border-bottom pb-2 mb-3">Exam Info</h6>
                <div class="mb-2"><small class="text-muted d-block">Exam Name</small><span class="fw-semibold">{{ $exam->name }}</span></div>
                <div class="mb-2"><small class="text-muted d-block">Type</small><span class="badge bg-primary">{{ $exam->examType?->name ?? '-' }}</span></div>
                <div class="mb-2"><small class="text-muted d-block">Start Date</small><span class="fw-semibold">{{ $exam->start_date?->format('d-m-Y') ?? '-' }}</span></div>
                <div class="mb-2"><small class="text-muted d-block">End Date</small><span class="fw-semibold">{{ $exam->end_date?->format('d-m-Y') ?? '-' }}</span></div>
                <div class="mb-2"><small class="text-muted d-block">Schedules</small><span class="fw-bold text-primary">{{ $exam->schedules_count }}</span></div>
                <div class="mb-2"><small class="text-muted d-block">Total Results</small><span class="fw-bold text-primary">{{ $exam->results_count }}</span></div>
                @if($exam->description)
                <div class="mb-2"><small class="text-muted d-block">Description</small><span class="fw-semibold">{{ $exam->description }}</span></div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-users me-2"></i>Student Results</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['#','Student','Subject','Marks','Total','Percentage','Grade','Status']">
                    @forelse($exam->results as $r)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $r->student?->first_name }} {{ $r->student?->last_name }}</td>
                        <td>{{ $r->subject?->name ?? '-' }}</td>
                        <td class="fw-bold">{{ $r->marks_obtained }}</td>
                        <td>{{ $r->total_marks }}</td>
                        <td>{{ $r->percentage }}%</td>
                        <td><span class="badge bg-{{ $r->percentage >= 75 ? 'success' : ($r->percentage >= 50 ? 'warning' : 'danger') }}">{{ $r->grade }}</span></td>
                        <td><span class="badge bg-{{ $r->status == 'passed' ? 'success' : 'danger' }}">{{ ucfirst($r->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-3">No results recorded.</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
</div>
@endsection
