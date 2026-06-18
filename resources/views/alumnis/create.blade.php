@extends('layouts.app')
@section('title', 'Add Alumni')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-user-plus me-2"></i>Add Alumni</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('alumni.index') }}">Alumni</a></li><li class="breadcrumb-item active">Add Alumni</li></ol></nav>
    </div>
</div>
<form>
    @csrf
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4"><x-form-input name="name" label="Full Name" required /></div>
                <div class="col-md-4"><x-form-input name="graduation_year" label="Graduation Year" type="number" required /></div>
                <div class="col-md-4"><x-form-input name="class" label="Class/Grade" placeholder="e.g. Grade 12" /></div>
                <div class="col-md-4"><x-form-input name="occupation" label="Current Occupation" /></div>
                <div class="col-md-4"><x-form-input name="company" label="Company/Organization" /></div>
                <div class="col-md-4"><x-form-input name="phone" label="Phone" type="tel" /></div>
                <div class="col-md-4"><x-form-input name="email" label="Email" type="email" /></div>
                <div class="col-md-4"><x-form-input name="linkedin" label="LinkedIn Profile" /></div>
                <div class="col-12"><x-form-textarea name="address" label="Address" rows="2" /></div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Alumni</button>
        <a href="{{ route('alumni.index') }}" class="btn btn-outline-danger"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection