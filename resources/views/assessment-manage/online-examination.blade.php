@extends('layouts.app')
@section('title', 'Online Examinations')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-laptop me-2"></i>Online Examinations</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Assessments</li><li class="breadcrumb-item active">Online Examinations</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="in-progress" {{ request('status') == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Subject</label>
                <select name="subject_id" class="form-select form-select-sm">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $s)
                    <option value="{{ $s->id }}" {{ request('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Exam title..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
            @if(request()->anyFilled(['status','subject_id','search']))
            <div class="col-12"><a href="{{ route('assessment.online-examination') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear</a></div>
            @endif
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Title</th><th>Subject</th><th>Class</th><th>Duration</th><th>Marks</th><th>Start</th><th>End</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($exams as $oe)
                    <tr>
                        <td>{{ $oe->id }}</td>
                        <td class="fw-semibold">{{ $oe->title }}</td>
                        <td>{{ $oe->subject->name ?? 'N/A' }}</td>
                        <td>{{ $oe->class->name ?? 'N/A' }}</td>
                        <td>{{ $oe->duration_minutes ?? '-' }} min</td>
                        <td>{{ $oe->total_marks ?? '-' }}</td>
                        <td>{{ $oe->start_time ? $oe->start_time->format('M d, Y H:i') : '-' }}</td>
                        <td>{{ $oe->end_time ? $oe->end_time->format('M d, Y H:i') : '-' }}</td>
                        <td>
                            @php
                                $sc = ['draft' => 'secondary', 'published' => 'success', 'in-progress' => 'warning', 'completed' => 'info'];
                                $s = $sc[$oe->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $s }} bg-opacity-10 text-{{ $s }}">{{ ucfirst($oe->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center py-4 text-muted">No online examinations found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<x-pagination :paginator="$exams" />
@endsection
