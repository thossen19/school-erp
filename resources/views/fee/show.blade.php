@extends('layouts.app')
@section('title', 'Fee Structure Detail')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-file-invoice-dollar me-2"></i>Fee Structure Detail</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('fees.index') }}">Fees</a></li><li class="breadcrumb-item active">Tuition Fee - Grade 10</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('fees.edit', 1) }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i>Edit</a>
        <button class="btn btn-outline-info"><i class="fas fa-copy me-1"></i>Duplicate</button>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-info me-2 text-primary"></i>Fee Information</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><small class="text-muted d-block">Fee Head</small><span class="fw-semibold">Tuition Fee</span></div>
                    <div class="col-md-6"><small class="text-muted d-block">Class</small><span class="fw-semibold">Grade 10</span></div>
                    <div class="col-md-6"><small class="text-muted d-block">Amount</small><span class="fw-bold fs-5 text-primary">$2,500.00</span></div>
                    <div class="col-md-6"><small class="text-muted d-block">Frequency</small><span class="fw-semibold">Monthly</span></div>
                    <div class="col-md-6"><small class="text-muted d-block">Due Day</small><span class="fw-semibold">15th of each month</span></div>
                    <div class="col-md-6"><small class="text-muted d-block">Late Fee</small><span class="fw-semibold text-danger">$25.00</span></div>
                    <div class="col-md-6"><small class="text-muted d-block">Status</small><span class="badge bg-success">Active</span></div>
                    <div class="col-md-6"><small class="text-muted d-block">Mandatory</small><span class="badge bg-info">Yes</span></div>
                    <div class="col-12"><small class="text-muted d-block">Description</small><span>Standard tuition fee for Grade 10 students</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-chart-bar me-2 text-success"></i>Collection Summary</h6></div>
            <div class="card-body">
                <div class="chart-container" style="height:200px"><canvas id="feeChart"></canvas></div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
new Chart(document.getElementById('feeChart'), { type:'doughnut', data:{ labels:['Collected','Pending','Overdue'], datasets:[{ data:[75,15,10], backgroundColor:['#198754','#ffc107','#dc3545'] }] }, options:{ responsive:true, maintainAspectRatio:false, plugins:{legend:{position:'bottom'}} } });
</script>
@endpush