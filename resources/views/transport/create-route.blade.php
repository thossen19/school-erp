@extends('layouts.app')
@section('title', 'Create Route')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-plus-circle me-2"></i>Create Route</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('transport.routes') }}">Routes</a></li><li class="breadcrumb-item active">Create</li></ol></nav>
    </div>
</div>
<form action="{{ route('transport.routes.store') }}" method="POST">
    @csrf
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6"><x-form-input name="route_name" label="Route Name" required value="{{ old('route_name') }}" /></div>
                <div class="col-md-3"><x-form-input name="route_number" label="Route Number" value="{{ old('route_number') }}" /></div>
                <div class="col-md-3"><x-form-input name="distance_km" label="Distance (km)" type="number" step="0.1" value="{{ old('distance_km') }}" /></div>
                <div class="col-md-6"><x-form-input name="start_point" label="Start Point" value="{{ old('start_point') }}" /></div>
                <div class="col-md-6"><x-form-input name="end_point" label="End Point" value="{{ old('end_point') }}" /></div>
                <div class="col-md-4"><x-form-select name="status" label="Status" required :options="['1'=>'Active','0'=>'Inactive']" /></div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Create</button>
        <a href="{{ route('transport.routes') }}" class="btn btn-outline-danger"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection
