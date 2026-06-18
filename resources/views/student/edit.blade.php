@extends('layouts.app')
@section('title', 'Edit Student')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-user-edit me-2"></i>Edit Student</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li><li class="breadcrumb-item"><a href="{{ route('student.show', 1) }}">John Doe</a></li><li class="breadcrumb-item active">Edit</li></ol></nav>
    </div>
</div>
<form>
    @csrf @method('PUT')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-user me-2 text-primary"></i>Personal Information</h6></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3"><x-form-input name="first_name" label="First Name" required value="John" /></div>
                <div class="col-md-3"><x-form-input name="middle_name" label="Middle Name" value="Michael" /></div>
                <div class="col-md-3"><x-form-input name="last_name" label="Last Name" required value="Doe" /></div>
                <div class="col-md-3"><x-form-input name="admission_no" label="Admission No." required value="ADM-2025001" /></div>
                <div class="col-md-3"><x-form-select name="gender" label="Gender" required :options="['male'=>'Male','female'=>'Female','other'=>'Other']" value="male" /></div>
                <div class="col-md-3"><x-form-input name="dob" label="Date of Birth" type="date" value="2010-01-15" /></div>
                <div class="col-md-3"><x-form-input name="blood_group" label="Blood Group" value="O+" /></div>
                <div class="col-md-3"><x-form-input name="nationality" label="Nationality" value="American" /></div>
                <div class="col-md-3"><x-form-input name="religion" label="Religion" value="Christian" /></div>
                <div class="col-md-3"><x-form-input name="phone" label="Phone" value="+1-555-0100" /></div>
                <div class="col-md-3"><x-form-input name="email" label="Email" value="john.doe@school.edu" /></div>
                <div class="col-md-3"><x-form-input name="aadhar_no" label="SSN No." value="XXX-XX-1234" /></div>
                <div class="col-12"><x-form-textarea name="address" label="Address" rows="2">123 Main Street, New York, NY 10001</x-form-textarea></div>
                <div class="col-md-4"><x-form-input name="city" label="City" value="New York" /></div>
                <div class="col-md-4"><x-form-select name="state" label="State" :options="['CA'=>'California','NY'=>'New York','TX'=>'Texas']" value="NY" /></div>
                <div class="col-md-4"><x-form-input name="zipcode" label="Zip Code" value="10001" /></div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-school me-2 text-info"></i>Academic Info</h6></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4"><x-form-select name="class" label="Class" :options="['10'=>'Grade 10']" value="10" /></div>
                <div class="col-md-4"><x-form-select name="section" label="Section" :options="['A'=>'A','B'=>'B','C'=>'C']" value="A" /></div>
                <div class="col-md-4"><x-form-select name="house" label="House" :options="['red'=>'Red House','blue'=>'Blue House','green'=>'Green House']" value="red" /></div>
                <div class="col-md-3"><x-form-input name="roll_number" label="Roll Number" value="15" /></div>
                <div class="col-md-3"><x-form-select name="status" label="Status" :options="['active'=>'Active','graduated'=>'Graduated','transferred'=>'Transferred','suspended'=>'Suspended']" value="active" /></div>
                <div class="col-md-3"><x-form-input name="admission_date" label="Admission Date" type="date" value="2025-03-15" /></div>
                <div class="col-md-3"><x-form-input name="graduation_date" label="Graduation Date (if any)" type="date" /></div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update Student</button>
        <a href="{{ route('student.show', 1) }}" class="btn btn-outline-secondary"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection