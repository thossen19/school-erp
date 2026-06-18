@extends('layouts.app')
@section('title', 'MIS Reports')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-file-alt me-2"></i>MIS Reports</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('mis.index') }}">MIS Reports</a></li><li class="breadcrumb-item active">Reports</li></ol></nav>
    </div>
    <button class="btn btn-outline-success"><i class="fas fa-file-excel me-1"></i>Export</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Report Type</label>
                <select class="form-select"><option>Student Report</option><option>Fee Report</option><option>Attendance Report</option><option>Staff Report</option></select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Academic Year</label>
                <select class="form-select"><option>2025-2026</option></select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Class</label>
                <select class="form-select"><option>All</option></select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Format</label>
                <select class="form-select"><option>PDF</option><option>Excel</option><option>CSV</option></select>
            </div>
            <div class="col-md-1"><button class="btn btn-primary w-100 mt-4"><i class="fas fa-download"></i></button></div>
        </div>
    </div>
</div>
<div class="card shadow-sm border-0 mt-3">
    <div class="card-body text-center py-5 text-muted">
        <i class="fas fa-chart-bar fa-4x mb-3"></i>
        <h5>Select Report Criteria</h5>
        <p>Choose a report type and filters above, then click generate to view the report.</p>
    </div>
</div>
@endsection