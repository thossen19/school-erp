@extends('layouts.app')
@section('title', 'Result Processing')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-cogs me-2"></i>Result Processing</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Assessments</li><li class="breadcrumb-item active">Result Processing</li></ol></nav>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-2 col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center"><div class="fs-3 fw-bold">{{ $stats['total_exams'] }}</div><small class="text-muted">Exams</small></div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center"><div class="fs-3 fw-bold">{{ $stats['total_results'] }}</div><small class="text-muted">Results</small></div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center"><div class="fs-3 fw-bold text-success">{{ $stats['passed'] }}</div><small class="text-muted">Passed</small></div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center"><div class="fs-3 fw-bold text-danger">{{ $stats['failed'] }}</div><small class="text-muted">Failed</small></div>
        </div>
    </div>
    <div class="col-xl-4 col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center"><div class="fs-3 fw-bold text-primary">{{ $stats['avg_percentage'] }}%</div><small class="text-muted">Avg Percentage</small></div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h6 class="fw-semibold mb-0"><i class="fas fa-chart-bar me-2"></i>Results by Exam</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Exam</th><th class="text-end">Results</th><th class="text-end">Avg %</th></tr>
                </thead>
                <tbody>
                    @forelse($byExam as $b)
                    <tr>
                        <td class="fw-semibold">{{ $b->exam->name ?? 'N/A' }}</td>
                        <td class="text-end"><span class="badge bg-primary bg-opacity-10 text-primary">{{ $b->total }}</span></td>
                        <td class="text-end"><span class="badge bg-{{ round($b->avg_pct) >= 50 ? 'success' : 'danger' }} bg-opacity-10 text-{{ round($b->avg_pct) >= 50 ? 'success' : 'danger' }}">{{ round($b->avg_pct, 1) }}%</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center py-3 text-muted">No exam results processed.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
