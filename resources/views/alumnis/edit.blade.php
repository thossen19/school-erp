@extends('layouts.app')
@section('title', 'Edit Alumni')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-edit me-2"></i>Edit Alumni</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('alumni.index') }}">Alumni</a></li><li class="breadcrumb-item"><a href="{{ route('alumni.show',1) }}">Alice Johnson</a></li><li class="breadcrumb-item active">Edit</li></ol></nav>
    </div>
</div>
<form>
    @csrf @method('PUT')
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4"><x-form-input name="name" label="Name" value="Alice Johnson" /></div>
                <div class="col-md-4"><x-form-input name="graduation_year" label="Graduation Year" type="number" value="2022" /></div>
                <div class="col-md-4"><x-form-input name="occupation" label="Occupation" value="Software Engineer" /></div>
                <div class="col-md-4"><x-form-input name="company" label="Company" value="Tech Corp" /></div>
                <div class="col-md-4"><x-form-input name="phone" label="Phone" value="+1-555-11001" /></div>
                <div class="col-md-4"><x-form-input name="email" label="Email" value="alice.j@email.com" /></div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
        <a href="{{ route('alumni.show',1) }}" class="btn btn-outline-secondary"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection