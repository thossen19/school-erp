@extends('layouts.app')
@section('title', 'Route Detail')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-bus me-2"></i>Route Detail</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('transport.index') }}">Transport</a></li><li class="breadcrumb-item active">Route A - North</li></ol></nav>
    </div>
    <div class="d-flex gap-2"><a href="{{ route('transport.edit',1) }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i>Edit</a></div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4"><small class="text-muted d-block">Route Name</small><span class="fw-semibold">Route A - North</span></div>
            <div class="col-md-4"><small class="text-muted d-block">Vehicle</small><span>Bus 101 (Toyota - 45 seats)</span></div>
            <div class="col-md-4"><small class="text-muted d-block">Driver</small><span>John Driver (+1-555-8001)</span></div>
            <div class="col-md-4"><small class="text-muted d-block">Start Point</small><span>Main School Gate</span></div>
            <div class="col-md-4"><small class="text-muted d-block">End Point</small><span>North Terminal</span></div>
            <div class="col-md-4"><small class="text-muted d-block">Distance</small><span>12.5 km</span></div>
            <div class="col-md-4"><small class="text-muted d-block">Start Time</small><span>7:00 AM</span></div>
            <div class="col-md-4"><small class="text-muted d-block">End Time</small><span>8:15 AM</span></div>
            <div class="col-md-4"><small class="text-muted d-block">Route Fee</small><span class="fw-bold text-primary">$50/month</span></div>
            <div class="col-12"><small class="text-muted d-block">Stops</small><span>Main Gate - Park Avenue - City Center - North Terminal</span></div>
            <div class="col-12"><small class="text-muted d-block">Allocated Students</small><span class="badge bg-info">32</span></div>
        </div>
    </div>
</div>
@endsection