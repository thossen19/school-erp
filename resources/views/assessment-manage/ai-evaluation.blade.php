@extends('layouts.app')
@section('title', 'AI Evaluation Support')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-robot me-2"></i>AI Evaluation Support</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Assessments</li><li class="breadcrumb-item active">AI Evaluation Support</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Evaluation Type</label>
                <select name="evaluation_type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    @foreach($evaluationTypes as $t)
                    <option value="{{ $t }}" {{ request('evaluation_type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                    @endforeach
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
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Student name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
            @if(request()->anyFilled(['evaluation_type','subject_id','search']))
            <div class="col-12"><a href="{{ route('assessment.ai-evaluation') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear</a></div>
            @endif
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Student</th><th>Type</th><th>Subject</th><th>Score</th><th>Status</th><th>Date</th></tr>
                </thead>
                <tbody>
                    @forelse($evaluations as $ev)
                    <tr>
                        <td>{{ $ev->id }}</td>
                        <td class="fw-semibold">{{ $ev->student->first_name ?? '' }} {{ $ev->student->last_name ?? '' }}</td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ ucfirst($ev->evaluation_type) }}</span></td>
                        <td>{{ $ev->subject->name ?? 'N/A' }}</td>
                        <td><span class="badge bg-{{ $ev->score && $ev->score >= 50 ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $ev->score && $ev->score >= 50 ? 'success' : 'secondary' }}">{{ $ev->score ?? '-' }}</span></td>
                        <td><span class="badge bg-{{ $ev->status == 'completed' ? 'success' : 'warning' }} bg-opacity-10 text-{{ $ev->status == 'completed' ? 'success' : 'warning' }}">{{ ucfirst($ev->status) }}</span></td>
                        <td>{{ $ev->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No AI evaluations found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<x-pagination :paginator="$evaluations" />
@endsection
