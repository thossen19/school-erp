@extends('layouts.app')
@section('title', 'KPI Dashboard')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-tachometer-alt me-2"></i>KPI Dashboard</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('mis.index') }}">MIS</a></li><li class="breadcrumb-item active">KPI</li></ol></nav>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-3"><x-stats-card title="Retention Rate" value="96.5%" icon="fa-user-check" color="success" /></div>
    <div class="col-md-3"><x-stats-card title="Pass Rate" value="89.2%" icon="fa-graduation-cap" color="primary" /></div>
    <div class="col-md-3"><x-stats-card title="Teacher:Student" value="1:13" icon="fa-users" color="info" /></div>
    <div class="col-md-3"><x-stats-card title="Facility Usage" value="78%" icon="fa-building" color="warning" /></div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0">Performance KPIs</h6></div>
            <div class="card-body">
                <div class="mb-3"><label>Student Satisfaction</label><div class="progress" style="height:10px"><div class="progress-bar bg-success" style="width:88%"></div></div><small>88%</small></div>
                <div class="mb-3"><label>Staff Retention</label><div class="progress" style="height:10px"><div class="progress-bar bg-primary" style="width:92%"></div></div><small>92%</small></div>
                <div class="mb-3"><label>Fee Collection Rate</label><div class="progress" style="height:10px"><div class="progress-bar bg-info" style="width:85%"></div></div><small>85%</small></div>
                <div class="mb-3"><label>Attendance Rate</label><div class="progress" style="height:10px"><div class="progress-bar bg-warning" style="width:94%"></div></div><small>94%</small></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0">School Scorecard</h6></div>
            <div class="card-body">
                <x-table :headers="['Indicator','Target','Actual','Status']">
                    <tr><td>Attendance</td><td>95%</td><td>94.2%</td><td><span class="badge bg-warning">Near Target</span></td></tr>
                    <tr><td>Fee Collection</td><td>90%</td><td>85%</td><td><span class="badge bg-info">In Progress</span></td></tr>
                    <tr><td>Pass Rate</td><td>90%</td><td>89.2%</td><td><span class="badge bg-success">Near Target</span></td></tr>
                    <tr><td>Student Satisfaction</td><td>90%</td><td>88%</td><td><span class="badge bg-success">Near Target</span></td></tr>
                </x-table>
            </div>
        </div>
    </div>
</div>
@endsection