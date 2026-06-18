@extends('layouts.app')
@section('title', 'Edit Student')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-user-graduate me-2"></i>Edit Student</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Students</a></li>
                <li class="breadcrumb-item"><a href="{{ route('students.show', $student->id) }}">{{ $student->admission_no ?? 'Details' }}</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="POST" action="{{ route('students.update', $student->id) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-4">
                    <x-form-input name="first_name" label="First Name" value="{{ $student->first_name }}" required />
                </div>
                <div class="col-md-4">
                    <x-form-input name="last_name" label="Last Name" value="{{ $student->last_name }}" required />
                </div>
                <div class="col-md-4">
                    <x-form-input name="admission_no" label="Admission No" value="{{ $student->admission_no }}" />
                </div>
                <div class="col-md-4">
                    <x-form-input name="date_of_birth" label="Date of Birth" type="date" value="{{ $student->date_of_birth?->format('Y-m-d') }}" required />
                </div>
                <div class="col-md-4">
                    <x-form-select name="gender" label="Gender" :options="['male'=>'Male','female'=>'Female','other'=>'Other']" :selected="$student->gender" required />
                </div>
                <div class="col-md-4">
                    <x-form-select name="class_id" label="Class" :options="$classes->pluck('name','id')" :selected="$student->class_id" required />
                </div>
                <div class="col-md-4">
                    <x-form-input name="phone" label="Phone" value="{{ $student->phone }}" />
                </div>
                <div class="col-md-4">
                    <x-form-input name="email" label="Email" type="email" value="{{ $student->email }}" />
                </div>
                <div class="col-md-4">
                    <x-form-input name="photo" label="Photo" type="file" />
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
                <a href="{{ route('students.show', $student->id) }}" class="btn btn-outline-secondary"><i class="fas fa-times me-1"></i>Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection