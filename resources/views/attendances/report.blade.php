@extends('layouts.app')
@section('title', 'Attendance Report')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chart-bar me-2"></i>Attendance Report</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('attendance.index') }}">Attendance</a></li><li class="breadcrumb-item active">Reports</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-success"><i class="fas fa-file-excel me-1"></i>Export Excel</button>
        <button class="btn btn-outline-danger"><i class="fas fa-file-pdf me-1"></i>Export PDF</button>
    </div>
</div>

<div class="filter-bar">
    <div class="row g-2 align-items-end">
        <div class="col-md-2"><label class="form-label fw-semibold small">From</label><input type="date" class="form-control form-control-sm" value="{{ date('Y-m-01') }}"></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">To</label><input type="date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}"></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Class</label><select class="form-select form-select-sm"><option>All</option></select></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Student</label><select class="form-select form-select-sm"><option>All</option></select></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Report Type</label><select class="form-select form-select-sm"><option>Daily Summary</option><option>Monthly Summary</option><option>Individual Report</option></select></div>
        <div class="col-md-1"><button class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button></div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><x-stats-card title="Avg. Attendance" value="92.5%" icon="fa-calendar-check" color="success" /></div>
    <div class="col-md-3"><x-stats-card title="Total Present" value="1,845" icon="fa-user-check" color="primary" /></div>
    <div class="col-md-3"><x-stats-card title="Total Absent" value="150" icon="fa-user-times" color="danger" /></div>
    <div class="col-md-3"><x-stats-card title="Attendance Rate" value="94.2%" icon="fa-percentage" color="info" /></div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-chart-line me-2"></i>Monthly Attendance Trend</h6></div>
    <div class="card-body"><div class="chart-container"><canvas id="attReportChart"></canvas></div></div>
</div>

<div class="card shadow-sm border-0 mt-3">
    <div class="card-body p-0">
        <x-table :headers="['Class','Total Students','Present','Absent','Late','Leave','Attendance %']">
            @foreach(range(1,6) as $i)
            <tr>
                <td><span class="fw-semibold">Grade {{ $i+6 }}</span></td>
                <td>{{ rand(30,45) }}</td>
                <td>{{ rand(25,43) }}</td>
                <td>{{ rand(0,5) }}</td>
                <td>{{ rand(0,3) }}</td>
                <td>{{ rand(0,2) }}</td>
                <td><span class="fw-bold text-success">{{ rand(85,99) }}%</span></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
@endsection
@push('scripts')
<script>
new Chart(document.getElementById('attReportChart'), {
    type: 'line',
    data: {
        labels: ['Week 1','Week 2','Week 3','Week 4'],
        datasets: [{
            label: 'Attendance %',
            data: [94, 92, 96, 93],
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13,110,253,0.1)',
            fill: true, tension: 0.4
        }]
    },
    options: { responsive:true, maintainAspectRatio:false }
});
</script>
@endpush