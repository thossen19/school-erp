@extends('layouts.app')
@section('title', 'Create Student')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-user-graduate me-2"></i>Create Student</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Students</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-4">
                    <x-form-input name="first_name" label="First Name" required />
                </div>
                <div class="col-md-4">
                    <x-form-input name="last_name" label="Last Name" required />
                </div>
                <div class="col-md-4">
                    <x-form-input name="admission_no" label="Admission No" />
                </div>
                <div class="col-md-4">
                    <x-form-input name="date_of_birth" label="Date of Birth" type="date" required />
                </div>
                <div class="col-md-4">
                    <x-form-select name="gender" label="Gender" :options="['male'=>'Male','female'=>'Female','other'=>'Other']" required />
                </div>
                <div class="col-md-4">
                    <x-form-select name="class_id" label="Class" :options="$classes->pluck('name','id')" required />
                </div>
                <div class="col-md-4">
                    <x-form-input name="phone" label="Phone" />
                </div>
                <div class="col-md-4">
                    <x-form-input name="email" label="Email" type="email" />
                </div>
                <div class="col-md-4">
                    <x-form-input name="photo" label="Photo" type="file" />
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save</button>
                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times me-1"></i>Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection