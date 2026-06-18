@extends('layouts.app')
@section('title', 'Exam Setup')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-calendar-check me-2"></i>Exam Setup</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Assessments</li><li class="breadcrumb-item active">Exam Setup</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Exam Type</label>
                <select name="exam_type_id" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    @foreach($examTypes as $t)
                    <option value="{{ $t->id }}" {{ request('exam_type_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Exam name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
            @if(request()->anyFilled(['exam_type_id','status','search']))
            <div class="col-12">
                <a href="{{ route('assessment.exam-setup') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear</a>
            </div>
            @endif
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Name</th><th>Type</th><th>Start</th><th>End</th><th>Schedules</th><th>Results</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($exams as $e)
                    <tr>
                        <td>{{ $e->id }}</td>
                        <td class="fw-semibold">{{ $e->name }}</td>
                        <td><span class="badge bg-info bg-opacity-10 text-info">{{ $e->examType->name ?? 'N/A' }}</span></td>
                        <td>{{ $e->start_date }}</td>
                        <td>{{ $e->end_date }}</td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $e->schedules_count }}</span></td>
                        <td><span class="badge bg-success bg-opacity-10 text-success">{{ $e->results_count }}</span></td>
                        <td>
                            @php $c = $e->status == 'active' ? 'success' : ($e->status == 'upcoming' ? 'warning' : 'secondary'); @endphp
                            <span class="badge bg-{{ $c }} bg-opacity-10 text-{{ $c }}">{{ ucfirst($e->status) }}</span>
                        </td>
                        <td><a href="{{ route('assessment.show', $e->id) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a></td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">No exams found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<x-pagination :paginator="$exams" />
@endsection
