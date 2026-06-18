@extends('layouts.app')
@section('title', 'Add Vehicle')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-plus-circle me-2"></i>Add Vehicle</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('transport.vehicles') }}">Vehicles</a></li><li class="breadcrumb-item active">Add</li></ol></nav>
    </div>
</div>
<form action="{{ route('transport.vehicles.store') }}" method="POST">
    @csrf
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4"><x-form-input name="vehicle_number" label="Vehicle No." required value="{{ old('vehicle_number') }}" /></div>
                <div class="col-md-4"><x-form-select name="vehicle_type" label="Type" required :options="['bus'=>'Bus','van'=>'Van','car'=>'Car']" /></div>
                <div class="col-md-4"><x-form-input name="vehicle_model" label="Model" value="{{ old('vehicle_model') }}" /></div>
                <div class="col-md-4"><x-form-input name="capacity" label="Capacity" type="number" required value="{{ old('capacity') }}" /></div>
                <div class="col-md-4"><x-form-input name="manufacturer" label="Manufacturer" value="{{ old('manufacturer') }}" /></div>
                <div class="col-md-4"><x-form-input name="year_of_manufacture" label="Year" type="number" value="{{ old('year_of_manufacture') }}" /></div>
                <div class="col-md-4"><x-form-input name="insurance_expiry" label="Insurance Expiry" type="date" value="{{ old('insurance_expiry') }}" /></div>
                <div class="col-md-4"><x-form-select name="status" label="Status" required :options="['active'=>'Active','maintenance'=>'Maintenance','retired'=>'Retired']" /></div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Add Vehicle</button>
        <a href="{{ route('transport.vehicles') }}" class="btn btn-outline-danger"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection
