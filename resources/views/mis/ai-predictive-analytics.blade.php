@extends('layouts.app')
@section('title', 'AI Predictive Analytics')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-robot me-2"></i>AI Predictive Analytics</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">MIS Reports</li><li class="breadcrumb-item active">AI Predictive Analytics</li></ol></nav>
    </div>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <x-stats-card icon="fa-users" title="Total Students" :value="$totalStudents" color="primary" />
    </div>
    <div class="col-md-2">
        <x-stats-card icon="fa-percentage" title="Avg Attendance" :value="$avgAttendance . '%'" color="success" />
    </div>
    <div class="col-md-2">
        <x-stats-card icon="fa-chart-line" title="Avg Performance" :value="round($avgPerformance, 1) . '%'" color="info" />
    </div>
    <div class="col-md-2">
        <x-stats-card icon="fa-check-circle" title="Fee Completion" :value="$feeCompletion . '%'" color="warning" />
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-exclamation-triangle me-2 text-danger"></i>At-Risk Students (Low Attendance)</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['#','Student','Admission No','Attendance']">
                    @forelse($atRiskStudents as $i => $s)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td class="fw-semibold">{{ $s->first_name }} {{ $s->last_name }}</td>
                        <td>{{ $s->admission_no }}</td>
                        <td><span class="badge bg-danger">{{ $s->attendance_rate ?? 0 }}%</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-3 text-muted">No at-risk students identified</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-exclamation-circle me-2 text-warning"></i>At-Risk Students (Pending Dues)</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['#','Student','Admission No','Balance']">
                    @forelse($atRiskFee as $i => $s)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td class="fw-semibold">{{ $s->first_name }} {{ $s->last_name }}</td>
                        <td>{{ $s->admission_no }}</td>
                        <td class="fw-bold text-danger">{{ number_format($s->balance_amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-3 text-muted">No students with pending dues</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
</div>
<div class="card shadow-sm border-0 mt-3">
    <div class="card-body text-center py-5">
        <i class="fas fa-microchip fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">AI-Powered Insights</h5>
        <p class="text-muted">Advanced predictive models for dropout risk, performance forecasting, and early intervention are coming soon.</p>
        <small class="text-muted">Currently showing heuristic-based risk indicators from attendance and fee data.</small>
    </div>
</div>
@endsection