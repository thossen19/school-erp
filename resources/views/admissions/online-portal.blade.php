@extends('layouts.app')
@section('title', 'Online Admission Portal')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-globe me-2"></i>Online Admission Portal</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('admissions.index') }}">Admissions</a></li><li class="breadcrumb-item active">Online Portal</li></ol></nav>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-edit me-2"></i>Submit Application</h6></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admissions.online-portal.submit') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-2 mb-2">
                        <div class="col-md-6"><x-form-input name="applicant_name" label="Full Name" required /></div>
                        <div class="col-md-3"><x-form-input name="date_of_birth" label="Date of Birth" type="date" required /></div>
                        <div class="col-md-3"><x-form-select name="gender" label="Gender" :options="['male'=>'Male','female'=>'Female','other'=>'Other']" required /></div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-md-4"><x-form-input name="phone" label="Phone" required /></div>
                        <div class="col-md-4"><x-form-input name="email" label="Email" required /></div>
                        <div class="col-md-4"><x-form-select name="class_applying_for_id" label="Applying for Class" :options="$classes->pluck('name','id')->toArray()" placeholder="Select Class" /></div>
                    </div>
                    <x-form-textarea name="address" label="Address" rows="2" />
                    <div class="mb-2"><label class="form-label small">Photo</label><input type="file" name="photo" class="form-control form-control-sm" accept="image/jpeg,image/png,image/jpg"></div>
                    <button type="submit" class="btn btn-primary mt-2"><i class="fas fa-paper-plane me-1"></i>Submit Application</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
