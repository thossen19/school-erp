@extends('layouts.app')
@section('title', 'Vehicle Details')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-bus me-2"></i>{{ $vehicle->vehicle_number }}</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('transport.vehicles') }}">Vehicles</a></li><li class="breadcrumb-item active">{{ $vehicle->vehicle_number }}</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4"><small class="text-muted d-block">Vehicle No.</small><span class="fw-semibold">{{ $vehicle->vehicle_number }}</span></div>
            <div class="col-md-4"><small class="text-muted d-block">Type</small><span class="fw-semibold">{{ ucfirst($vehicle->vehicle_type ?? '-') }}</span></div>
            <div class="col-md-4"><small class="text-muted d-block">Model</small><span class="fw-semibold">{{ $vehicle->vehicle_model ?? '-' }}</span></div>
            <div class="col-md-4"><small class="text-muted d-block">Manufacturer</small><span class="fw-semibold">{{ $vehicle->manufacturer ?? '-' }}</span></div>
            <div class="col-md-4"><small class="text-muted d-block">Capacity</small><span class="fw-bold fs-5 text-primary">{{ $vehicle->capacity }}</span></div>
            <div class="col-md-4"><small class="text-muted d-block">Year</small><span class="fw-semibold">{{ $vehicle->year_of_manufacture ?? '-' }}</span></div>
            <div class="col-md-4"><small class="text-muted d-block">Insurance Expiry</small><span>{{ $vehicle->insurance_expiry ? \Carbon\Carbon::parse($vehicle->insurance_expiry)->format('d-m-Y') : '-' }}</span></div>
            <div class="col-md-4"><small class="text-muted d-block">Status</small>
                @php $b = match($vehicle->status) { 'active' => 'success', 'maintenance' => 'warning', 'retired' => 'danger', default => 'secondary' }; @endphp
                <span class="badge bg-{{ $b }}">{{ ucfirst($vehicle->status) }}</span>
            </div>
        </div>
    </div>
</div>
<div class="d-flex gap-2 mt-3">
    <a href="{{ route('transport.vehicles') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
</div>
@endsection
