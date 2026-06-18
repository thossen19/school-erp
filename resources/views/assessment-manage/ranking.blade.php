@extends('layouts.app')
@section('title', 'Ranking')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-trophy me-2"></i>Ranking</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Assessments</li><li class="breadcrumb-item active">Ranking</li></ol></nav>
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
            <div class="col-md-5">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Student name/no..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
            @if(request()->anyFilled(['exam_id','search']))
            <div class="col-12"><a href="{{ route('assessment.ranking') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear</a></div>
            @endif
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h6 class="fw-semibold mb-0"><i class="fas fa-list-ol me-2"></i>Student Rankings</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Rank</th><th>Student</th><th>Avg %</th><th>Exams</th><th>Passed</th><th>Failed</th></tr>
                </thead>
                <tbody>
                    @forelse($rankings as $i => $r)
                    @php $rank = ($rankings->currentPage() - 1) * $rankings->perPage() + $i + 1; @endphp
                    <tr>
                        <td>
                            @if($rank == 1)<span class="badge bg-warning text-dark fs-6"><i class="fas fa-crown"></i></span>
                            @elseif($rank == 2)<span class="badge bg-secondary text-light fs-6">2nd</span>
                            @elseif($rank == 3)<span class="badge bg-danger text-light fs-6">3rd</span>
                            @else<span class="badge bg-light text-dark">{{ $rank }}</span>@endif
                        </td>
                        <td class="fw-semibold">{{ $r->student->first_name ?? '' }} {{ $r->student->last_name ?? '' }}<br><small class="text-muted">{{ $r->student->admission_no ?? '' }}</small></td>
                        <td><span class="badge bg-{{ $r->avg_percentage >= 75 ? 'success' : ($r->avg_percentage >= 50 ? 'warning' : 'danger') }} bg-opacity-10 text-{{ $r->avg_percentage >= 75 ? 'success' : ($r->avg_percentage >= 50 ? 'warning' : 'danger') }} fs-6">{{ round($r->avg_percentage, 1) }}%</span></td>
                        <td>{{ $r->exams_attempted }}</td>
                        <td><span class="text-success">{{ $r->passed }}</span></td>
                        <td><span class="text-danger">{{ $r->failed }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted">No rankings available.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<x-pagination :paginator="$rankings" />
@endsection
