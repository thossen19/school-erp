@extends('layouts.app')
@section('title', 'Performance Analytics')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-chart-pie me-2"></i>Performance Analytics</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Assessments</li><li class="breadcrumb-item active">Performance Analytics</li></ol></nav>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center"><div class="fs-3 fw-bold">{{ $totalStudents }}</div><small class="text-muted">Students</small></div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center"><div class="fs-3 fw-bold">{{ $totalExams }}</div><small class="text-muted">Exams</small></div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center"><div class="fs-3 fw-bold">{{ $totalResults }}</div><small class="text-muted">Results</small></div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center"><div class="fs-3 fw-bold text-primary">{{ $overallAvg }}%</div><small class="text-muted">Overall Avg</small></div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-circle me-2"></i>Pass/Fail Distribution</h6></div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Status</th><th class="text-end">Count</th></tr></thead>
                    <tbody>
                        @forelse($passFailStats as $s)
                        <tr><td>{{ ucfirst($s->status) }}</td><td class="text-end"><span class="badge bg-{{ $s->status == 'passed' ? 'success' : 'danger' }} bg-opacity-10 text-{{ $s->status == 'passed' ? 'success' : 'danger' }} fs-6">{{ $s->total }}</span></td></tr>
                        @empty
                        <tr><td colspan="2" class="text-center py-3 text-muted">No data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-header bg-white py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-user-graduate me-2"></i>Top Performers</h6></div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>#</th><th>Student</th><th class="text-end">Avg %</th></tr></thead>
                    <tbody>
                        @forelse($topPerformers as $i => $p)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="fw-semibold">{{ $p->student->first_name ?? '' }} {{ $p->student->last_name ?? '' }}</td>
                            <td class="text-end"><span class="badge bg-success bg-opacity-10 text-success fs-6">{{ round($p->avg_pct, 1) }}%</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-3 text-muted">No data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-book me-2"></i>Avg by Subject</h6></div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Subject</th><th class="text-end">Avg %</th><th class="text-end">Results</th></tr></thead>
                    <tbody>
                        @forelse($avgBySubject as $s)
                        <tr>
                            <td>{{ $s->subject->name ?? 'N/A' }}</td>
                            <td class="text-end"><span class="badge bg-{{ round($s->avg_pct) >= 50 ? 'success' : 'danger' }} bg-opacity-10 text-{{ round($s->avg_pct) >= 50 ? 'success' : 'danger' }}">{{ round($s->avg_pct, 1) }}%</span></td>
                            <td class="text-end">{{ $s->total }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-3 text-muted">No data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-header bg-white py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-calendar-alt me-2"></i>Monthly Trend</h6></div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Month</th><th class="text-end">Results</th><th class="text-end">Avg %</th></tr></thead>
                    <tbody>
                        @forelse($monthlyTrend as $m)
                        <tr>
                            <td>{{ DateTime::createFromFormat('!m', $m->month)->format('F Y') }}</td>
                            <td class="text-end">{{ $m->total }}</td>
                            <td class="text-end"><span class="badge bg-primary bg-opacity-10 text-primary">{{ round($m->avg_pct, 1) }}%</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-3 text-muted">No data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
