@extends('layouts.app')
@section('title', 'Add Route')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-plus-circle me-2"></i>Add Transport Route</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('transport.index') }}">Transport</a></li><li class="breadcrumb-item active">Add Route</li></ol></nav>
    </div>
</div>
<form>
    @csrf
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4"><x-form-input name="route_name" label="Route Name" required placeholder="e.g. Route A - North" /></div>
                <div class="col-md-4"><x-form-select name="vehicle" label="Vehicle" :options="['B101'=>'Bus 101','B102'=>'Bus 102','B103'=>'Bus 103']" /></div>
                <div class="col-md-4"><x-form-select name="driver" label="Driver" :options="['1'=>'John Driver','2'=>'Mike Driver']" /></div>
                <div class="col-md-4"><x-form-input name="start_point" label="Start Point" placeholder="Starting location" /></div>
                <div class="col-md-4"><x-form-input name="end_point" label="End Point" placeholder="End location" /></div>
                <div class="col-md-4"><x-form-input name="distance" label="Distance (km)" type="number" step="0.1" /></div>
                <div class="col-md-4"><x-form-input name="start_time" label="Start Time" type="time" /></div>
                <div class="col-md-4"><x-form-input name="end_time" label="End Time" type="time" /></div>
                <div class="col-md-4"><x-form-input name="fee" label="Route Fee ($)" type="number" step="0.01" /></div>
                <div class="col-12"><x-form-textarea name="stops" label="Stops (one per line)" rows="3" placeholder="Stop 1&#10;Stop 2&#10;Stop 3" /></div>
                <div class="col-12"><x-form-textarea name="notes" label="Notes" rows="2" /></div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Route</button>
        <a href="{{ route('transport.index') }}" class="btn btn-outline-danger"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection