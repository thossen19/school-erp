@extends('layouts.app')
@section('title', 'Add Driver')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-plus-circle me-2"></i>Add Driver</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('transport.drivers') }}">Drivers</a></li><li class="breadcrumb-item active">Add</li></ol></nav>
    </div>
</div>
<form action="{{ route('transport.drivers.store') }}" method="POST">
    @csrf
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6"><x-form-input name="first_name" label="First Name" required value="{{ old('first_name') }}" /></div>
                <div class="col-md-6"><x-form-input name="last_name" label="Last Name" value="{{ old('last_name') }}" /></div>
                <div class="col-md-4"><x-form-input name="phone" label="Phone" required value="{{ old('phone') }}" /></div>
                <div class="col-md-4"><x-form-input name="email" label="Email" type="email" value="{{ old('email') }}" /></div>
                <div class="col-md-4"><x-form-input name="license_number" label="License No." required value="{{ old('license_number') }}" /></div>
                <div class="col-md-4"><x-form-input name="license_expiry" label="License Expiry" type="date" required value="{{ old('license_expiry') }}" /></div>
                <div class="col-md-4"><x-form-select name="status" label="Status" required :options="['active'=>'Active','inactive'=>'Inactive','suspended'=>'Suspended']" /></div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Add Driver</button>
        <a href="{{ route('transport.drivers') }}" class="btn btn-outline-danger"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection
