@extends('layouts.app')
@section('title', 'Admission Workflow')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-project-diagram me-2"></i>Admission Workflow</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('admissions.index') }}">Admissions</a></li><li class="breadcrumb-item active">Workflow</li></ol></nav>
    </div>
</div>

<div class="row g-3">
    @foreach($stages as $stage)
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-start border-4 border-{{ $stage['color'] }} h-100">
            <div class="card-body text-center">
                <div class="display-6 text-{{ $stage['color'] }} mb-2"><i class="fas {{ $stage['icon'] }}"></i></div>
                <h6 class="fw-semibold">{{ $stage['name'] }}</h6>
                <span class="fs-3 fw-bold text-{{ $stage['color'] }}">{{ $stage['count'] }}</span>
            </div>
            <div class="card-footer bg-white text-center border-0 pt-0">
                <small class="text-muted">{{ $stage['name'] }} Records</small>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="card shadow-sm border-0 mt-3">
    <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-arrow-right me-2"></i>Admission Pipeline</h6></div>
    <div class="card-body">
        <div class="progress" style="height:30px">
            @php $total = max(array_sum(array_column($stages, 'count')), 1); @endphp
            @foreach($stages as $s)
            <div class="progress-bar bg-{{ $s['color'] }}" role="progressbar" style="width:{{ round($s['count']/$total*100) }}%" title="{{ $s['name'] }}: {{ $s['count'] }}">
                <small>{{ $s['name'] }}</small>
            </div>
            @endforeach
        </div>
        <small class="text-muted mt-1 d-block">Pipeline distribution across admission stages</small>
    </div>
</div>
@endsection
