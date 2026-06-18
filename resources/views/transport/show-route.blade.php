@extends('layouts.app')
@section('title', 'Route Details')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-route me-2"></i>{{ $route->route_name }}</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('transport.routes') }}">Routes</a></li><li class="breadcrumb-item active">{{ $route->route_name }}</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3"><small class="text-muted d-block">Route Number</small><span class="fw-semibold">{{ $route->route_number ?? '-' }}</span></div>
            <div class="col-md-3"><small class="text-muted d-block">Start Point</small><span class="fw-semibold">{{ $route->start_point ?? '-' }}</span></div>
            <div class="col-md-3"><small class="text-muted d-block">End Point</small><span class="fw-semibold">{{ $route->end_point ?? '-' }}</span></div>
            <div class="col-md-3"><small class="text-muted d-block">Distance</small><span class="fw-semibold">{{ $route->distance_km ?? '-' }} km</span></div>
            <div class="col-md-3"><small class="text-muted d-block">Stops</small><span class="fw-bold fs-5 text-primary">{{ $route->total_stops ?? $route->stops ?? 0 }}</span></div>
            <div class="col-md-3"><small class="text-muted d-block">Status</small><span class="badge bg-{{ $route->status ? 'success' : 'danger' }}">{{ $route->status ? 'Active' : 'Inactive' }}</span></div>
        </div>
    </div>
</div>
<div class="d-flex gap-2 mt-3">
    <a href="{{ route('transport.routes') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
</div>
@endsection
