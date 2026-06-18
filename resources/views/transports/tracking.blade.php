@extends('layouts.app')
@section('title', 'Transport Tracking')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-map-marker-alt me-2"></i>Transport Tracking</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('transport.index') }}">Transport</a></li><li class="breadcrumb-item active">Tracking</li></ol></nav>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-md-3"><select class="form-select"><option>All Routes</option><option>Route A - North</option><option>Route B - South</option></select></div>
            <div class="col-md-3"><select class="form-select"><option>All Buses</option><option>Bus 101</option><option>Bus 102</option></select></div>
            <div class="col-md-3"><button class="btn btn-primary"><i class="fas fa-sync-alt me-1"></i>Refresh</button></div>
        </div>
        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height:400px;border:2px dashed #ccc;">
            <div class="text-center text-muted">
                <i class="fas fa-map-marked-alt fa-4x mb-3"></i>
                <h5>Live Map View</h5>
                <p>Real-time GPS tracking would be displayed here<br>using Google Maps or similar service.</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-2">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="fw-semibold"><i class="fas fa-bus text-primary me-1"></i> Bus 101</h6>
                <small class="text-muted d-block">Route A - North</small>
                <small class="text-success"><i class="fas fa-circle me-1"></i>On Time</small>
                <small class="d-block mt-1">Driver: John Driver | Speed: 35 km/h</small>
                <small>Next Stop: Park Avenue (5 min)</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="fw-semibold"><i class="fas fa-bus text-success me-1"></i> Bus 102</h6>
                <small class="text-muted d-block">Route B - South</small>
                <small class="text-success"><i class="fas fa-circle me-1"></i>On Time</small>
                <small class="d-block mt-1">Driver: Mike Driver | Speed: 40 km/h</small>
                <small>Next Stop: City Center (8 min)</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="fw-semibold"><i class="fas fa-bus text-warning me-1"></i> Bus 103</h6>
                <small class="text-muted d-block">Route C - East</small>
                <small class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>Delayed (10 min)</small>
                <small class="d-block mt-1">Driver: Steve Driver | Speed: 20 km/h</small>
                <small>Next Stop: Eastside (delayed due to traffic)</small>
            </div>
        </div>
    </div>
</div>
@endsection