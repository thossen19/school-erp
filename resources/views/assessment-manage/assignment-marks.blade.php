@extends('layouts.app')
@section('title', 'Assignment Marks')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-tasks me-2"></i>Assignment Marks</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Assessments</li><li class="breadcrumb-item active">Assignment Marks</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Assignment</label>
                <select name="assignment_id" class="form-select form-select-sm">
                    <option value="">All Assignments</option>
                    @foreach($assignments as $a)
                    <option value="{{ $a->id }}" {{ request('assignment_id') == $a->id ? 'selected' : '' }}>{{ $a->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Student name/no..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
            @if(request()->anyFilled(['assignment_id','search']))
            <div class="col-12"><a href="{{ route('assessment.assignment-marks') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear</a></div>
            @endif
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Student</th><th>Assignment</th><th>Marks</th><th>Feedback</th><th>Submitted</th></tr>
                </thead>
                <tbody>
                    @forelse($submissions as $s)
                    <tr>
                        <td>{{ $s->id }}</td>
                        <td class="fw-semibold">{{ $s->student->first_name ?? '' }} {{ $s->student->last_name ?? '' }}<br><small class="text-muted">{{ $s->student->admission_no ?? '' }}</small></td>
                        <td>{{ $s->assignment->title ?? 'N/A' }}</td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary fs-6">{{ $s->marks ?? '-' }}</span></td>
                        <td class="text-truncate" style="max-width:200px;">{{ $s->feedback ?? '-' }}</td>
                        <td>{{ $s->submitted_at ? $s->submitted_at->format('M d, Y') : '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">No assignment marks found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<x-pagination :paginator="$submissions" />
@endsection
