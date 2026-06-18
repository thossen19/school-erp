@extends('layouts.app')
@section('title', 'MIS Reports')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chart-bar me-2"></i>MIS Reports</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">MIS Reports</li></ol></nav>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center h-100">
            <div class="card-body">
                <i class="fas fa-user-graduate fa-3x text-primary mb-3"></i>
                <h6 class="fw-semibold">Student Reports</h6>
                <small class="text-muted d-block mb-2">Enrollment, demographics, performance</small>
                <button class="btn btn-sm btn-outline-primary">View Reports</button>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center h-100">
            <div class="card-body">
                <i class="fas fa-money-bill-wave fa-3x text-success mb-3"></i>
                <h6 class="fw-semibold">Financial Reports</h6>
                <small class="text-muted d-block mb-2">Fee collection, revenue, expenses</small>
                <button class="btn btn-sm btn-outline-success">View Reports</button>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center h-100">
            <div class="card-body">
                <i class="fas fa-chart-line fa-3x text-info mb-3"></i>
                <h6 class="fw-semibold">Academic Reports</h6>
                <small class="text-muted d-block mb-2">Results, performance, trends</small>
                <button class="btn btn-sm btn-outline-info">View Reports</button>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center h-100">
            <div class="card-body">
                <i class="fas fa-users fa-3x text-warning mb-3"></i>
                <h6 class="fw-semibold">Staff Reports</h6>
                <small class="text-muted d-block mb-2">HR, payroll, attendance</small>
                <button class="btn btn-sm btn-outline-warning">View Reports</button>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center h-100">
            <div class="card-body">
                <i class="fas fa-calendar-check fa-3x text-danger mb-3"></i>
                <h6 class="fw-semibold">Attendance Reports</h6>
                <small class="text-muted d-block mb-2">Daily, monthly, yearly summaries</small>
                <button class="btn btn-sm btn-outline-danger">View Reports</button>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center h-100">
            <div class="card-body">
                <i class="fas fa-trophy fa-3x text-primary mb-3"></i>
                <h6 class="fw-semibold">Performance Reports</h6>
                <small class="text-muted d-block mb-2">Exams, rankings, achievements</small>
                <button class="btn btn-sm btn-outline-primary">View Reports</button>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center h-100">
            <div class="card-body">
                <i class="fas fa-calculator fa-3x text-success mb-3"></i>
                <h6 class="fw-semibold">Accounting Reports</h6>
                <small class="text-muted d-block mb-2">Ledger, P&L, balance sheet</small>
                <button class="btn btn-sm btn-outline-success">View Reports</button>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center h-100">
            <div class="card-body">
                <i class="fas fa-cog fa-3x text-secondary mb-3"></i>
                <h6 class="fw-semibold">Custom Reports</h6>
                <small class="text-muted d-block mb-2">Build your own reports</small>
                <button class="btn btn-sm btn-outline-secondary">Create Report</button>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mt-3">
    <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-chart-bar me-2"></i>Quick Analytics</h6></div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6"><div class="chart-container"><canvas id="misChart"></canvas></div></div>
            <div class="col-md-6">
                <x-table :headers="['Metric','Current','Previous','Change']">
                    <tr><td>Total Students</td><td>1,248</td><td>1,112</td><td><span class="text-success">+12.2%</span></td></tr>
                    <tr><td>Attendance Rate</td><td>94.2%</td><td>92.8%</td><td><span class="text-success">+1.4%</span></td></tr>
                    <tr><td>Fee Collection</td><td>$284,500</td><td>$252,000</td><td><span class="text-success">+12.9%</span></td></tr>
                    <tr><td>Pass Percentage</td><td>89.5%</td><td>87.2%</td><td><span class="text-success">+2.3%</span></td></tr>
                </x-table>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
new Chart(document.getElementById('misChart'), { type:'bar', data:{ labels:['Students','Teachers','Staff','Classes'], datasets:[{ label:'Current Year', data:[1248,96,45,42], backgroundColor:'rgba(13,110,253,0.6)' },{ label:'Previous Year', data:[1112,88,40,38], backgroundColor:'rgba(25,135,84,0.6)' }] }, options:{ responsive:true, maintainAspectRatio:false } });
</script>
@endpush