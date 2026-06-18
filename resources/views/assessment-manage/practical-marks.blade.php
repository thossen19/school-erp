@extends('layouts.app')
@section('title', 'Practical Marks')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-flask me-2"></i>Practical Marks</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Assessments</li><li class="breadcrumb-item active">Practical Marks</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Subject</label>
                <select name="subject_id" class="form-select form-select-sm">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $s)
                    <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Exam</label>
                <select name="exam_id" class="form-select form-select-sm">
                    <option value="">All Exams</option>
                    @foreach($exams as $ex)
                    <option value="{{ $ex->id }}" {{ request('exam_id') == $ex->id ? 'selected' : '' }}>{{ $ex->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Student name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
            @if(request()->anyFilled(['subject_id','exam_id','status','search']))
            <div class="col-12"><a href="{{ route('assessment.practical-marks') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear</a></div>
            @endif
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Student</th><th>Subject</th><th>Exam</th><th>Marks</th><th>Date</th><th>Grade</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($marks as $m)
                    <tr>
                        <td>{{ $m->id }}</td>
                        <td class="fw-semibold">{{ $m->student->first_name ?? '' }} {{ $m->student->last_name ?? '' }}</td>
                        <td>{{ $m->subject->name ?? 'N/A' }}</td>
                        <td>{{ $m->exam->name ?? 'N/A' }}</td>
                        <td>{{ $m->marks_obtained }} / {{ $m->total_marks }}</td>
                        <td>{{ $m->practical_date ?? '-' }}</td>
                        <td>{{ $m->grade ?? '-' }}</td>
                        <td><span class="badge bg-{{ $m->status == 'completed' ? 'success' : ($m->status == 'pending' ? 'warning' : 'secondary') }} bg-opacity-10 text-{{ $m->status == 'completed' ? 'success' : ($m->status == 'pending' ? 'warning' : 'secondary') }}">{{ ucfirst($m->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">No practical marks found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<x-pagination :paginator="$marks" />
@endsection
