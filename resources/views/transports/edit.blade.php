@extends('layouts.app')
@section('title', 'Edit Route')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-edit me-2"></i>Edit Route</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('transport.index') }}">Transport</a></li><li class="breadcrumb-item"><a href="{{ route('transport.show',1) }}">Route A</a></li><li class="breadcrumb-item active">Edit</li></ol></nav>
    </div>
</div>
<form>
    @csrf @method('PUT')
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4"><x-form-input name="route_name" label="Route Name" value="Route A - North" /></div>
                <div class="col-md-4"><x-form-select name="vehicle" label="Vehicle" :options="['B101'=>'Bus 101']" value="B101" /></div>
                <div class="col-md-4"><x-form-select name="driver" label="Driver" :options="['1'=>'John Driver']" value="1" /></div>
                <div class="col-md-4"><x-form-input name="start_point" label="Start Point" value="Main School Gate" /></div>
                <div class="col-md-4"><x-form-input name="end_point" label="End Point" value="North Terminal" /></div>
                <div class="col-md-4"><x-form-input name="fee" label="Fee ($)" type="number" value="50" /></div>
                <div class="col-12"><x-form-textarea name="stops" label="Stops" rows="2">Main Gate, Park Avenue, City Center</x-form-textarea></div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
        <a href="{{ route('transport.show',1) }}" class="btn btn-outline-secondary"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection