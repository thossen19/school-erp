@extends('layouts.app')
@section('title', 'Continuous Assessment')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-chart-line me-2"></i>Continuous Assessment</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Assessments</li><li class="breadcrumb-item active">Continuous Assessment</li></ol></nav>
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
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Type</label>
                <select name="assessment_type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    @foreach($types as $t)
                    <option value="{{ $t }}" {{ request('assessment_type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Student name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
            @if(request()->anyFilled(['subject_id','assessment_type','search']))
            <div class="col-12"><a href="{{ route('assessment.continuous-assessment') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear</a></div>
            @endif
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Student</th><th>Subject</th><th>Type</th><th>Title</th><th>Marks</th><th>%</th><th>Grade</th><th>Date</th></tr>
                </thead>
                <tbody>
                    @forelse($assessments as $ca)
                    <tr>
                        <td>{{ $ca->id }}</td>
                        <td class="fw-semibold">{{ $ca->student->first_name ?? '' }} {{ $ca->student->last_name ?? '' }}</td>
                        <td>{{ $ca->subject->name ?? 'N/A' }}</td>
                        <td><span class="badge bg-info bg-opacity-10 text-info">{{ ucfirst($ca->assessment_type) }}</span></td>
                        <td>{{ $ca->title ?? '-' }}</td>
                        <td>{{ $ca->marks_obtained }} / {{ $ca->max_marks }}</td>
                        <td>
                            @php $pct = $ca->max_marks > 0 ? round(($ca->marks_obtained / $ca->max_marks) * 100, 1) : 0; @endphp
                            <span class="badge bg-{{ $pct >= 50 ? 'success' : 'danger' }} bg-opacity-10 text-{{ $pct >= 50 ? 'success' : 'danger' }}">{{ $pct }}%</span>
                        </td>
                        <td>{{ $ca->grade ?? '-' }}</td>
                        <td>{{ $ca->assessment_date }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">No continuous assessments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<x-pagination :paginator="$assessments" />
@endsection
