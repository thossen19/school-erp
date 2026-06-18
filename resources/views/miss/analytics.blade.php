@extends('layouts.app')
@section('title', 'Analytics')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chart-pie me-2"></i>Analytics Dashboard</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('mis.index') }}">MIS</a></li><li class="breadcrumb-item active">Analytics</li></ol></nav>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-0"><div class="card-header bg-transparent py-3"><h6 class="fw-semibold mb-0">Student Enrollment Trend</h6></div>
        <div class="card-body"><div class="chart-container"><canvas id="enrollChart"></canvas></div></div></div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0"><div class="card-header bg-transparent py-3"><h6 class="fw-semibold mb-0">Gender Distribution</h6></div>
        <div class="card-body"><div class="chart-container"><canvas id="genderChart"></canvas></div></div></div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0"><div class="card-header bg-transparent py-3"><h6 class="fw-semibold mb-0">Monthly Fee Collection</h6></div>
        <div class="card-body"><div class="chart-container"><canvas id="feeChart2"></canvas></div></div></div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0"><div class="card-header bg-transparent py-3"><h6 class="fw-semibold mb-0">Class-wise Performance</h6></div>
        <div class="card-body"><div class="chart-container"><canvas id="perfChart"></canvas></div></div></div>
    </div>
</div>
@endsection
@push('scripts')
<script>
['enrollChart','genderChart','feeChart2','perfChart'].forEach(id => {
    new Chart(document.getElementById(id), {
        type: id=='genderChart'?'doughnut':'bar',
        data: { labels: id=='genderChart'?['Boys','Girls']:['Jan','Feb','Mar','Apr','May'], datasets: [{ data: id=='genderChart'?[55,45]:[45,52,68,74,89], backgroundColor: id=='genderChart'?['#0d6efd','#dc3545']:'rgba(13,110,253,0.6)' }] },
        options: { responsive:true, maintainAspectRatio:false, plugins:{legend:{position:'bottom'}} }
    });
});
</script>
@endpush