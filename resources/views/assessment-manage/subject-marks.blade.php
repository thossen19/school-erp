@extends('layouts.app')
@section('title', 'Subject Marks')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-book me-2"></i>Subject Marks</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Assessments</li><li class="breadcrumb-item active">Subject Marks</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
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
                <label class="form-label fw-semibold small">Subject</label>
                <select name="subject_id" class="form-select form-select-sm">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $s)
                    <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="passed" {{ request('status') == 'passed' ? 'selected' : '' }}>Passed</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Student name/no..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
            @if(request()->anyFilled(['exam_id','subject_id','status','search']))
            <div class="col-12"><a href="{{ route('assessment.subject-marks') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear</a></div>
            @endif
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Student</th><th>Exam</th><th>Subject</th><th>Marks</th><th>Percentage</th><th>Grade</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($results as $r)
                    <tr>
                        <td>{{ $r->id }}</td>
                        <td class="fw-semibold">{{ $r->student->first_name ?? '' }} {{ $r->student->last_name ?? '' }}<br><small class="text-muted">{{ $r->student->admission_no ?? '' }}</small></td>
                        <td>{{ $r->exam->name ?? 'N/A' }}</td>
                        <td>{{ $r->subject->name ?? 'N/A' }}</td>
                        <td>{{ $r->marks_obtained }} / {{ $r->total_marks }}</td>
                        <td>
                            @php $p = $r->percentage ?? ($r->total_marks > 0 ? round(($r->marks_obtained / $r->total_marks) * 100, 1) : 0); @endphp
                            <span class="badge bg-{{ $p >= 50 ? 'success' : 'danger' }} bg-opacity-10 text-{{ $p >= 50 ? 'success' : 'danger' }}">{{ $p }}%</span>
                        </td>
                        <td>{{ $r->grade ?? '-' }}</td>
                        <td><span class="badge bg-{{ $r->status == 'passed' ? 'success' : 'danger' }} bg-opacity-10 text-{{ $r->status == 'passed' ? 'success' : 'danger' }}">{{ ucfirst($r->status ?? 'N/A') }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">No marks found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<x-pagination :paginator="$results" />
@endsection
