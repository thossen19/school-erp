@extends('layouts.app')
@section('title', 'Add Student')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-user-plus me-2"></i>Add New Student</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li><li class="breadcrumb-item active">Add New</li></ol></nav>
    </div>
</div>

<form>
    @csrf
    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-user me-2 text-primary"></i>Personal Information</h6></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3"><x-form-input name="first_name" label="First Name" required placeholder="Enter first name" /></div>
                <div class="col-md-3"><x-form-input name="middle_name" label="Middle Name" placeholder="Enter middle name" /></div>
                <div class="col-md-3"><x-form-input name="last_name" label="Last Name" required placeholder="Enter last name" /></div>
                <div class="col-md-3"><x-form-input name="admission_no" label="Admission No." required placeholder="ADM-0001" /></div>
                <div class="col-md-3"><x-form-select name="gender" label="Gender" required :options="['male'=>'Male','female'=>'Female','other'=>'Other']" /></div>
                <div class="col-md-3"><x-form-input name="dob" label="Date of Birth" type="date" required /></div>
                <div class="col-md-3"><x-form-input name="blood_group" label="Blood Group" placeholder="e.g. O+" /></div>
                <div class="col-md-3"><x-form-input name="nationality" label="Nationality" value="American" /></div>
                <div class="col-md-3"><x-form-input name="religion" label="Religion" placeholder="e.g. Christian" /></div>
                <div class="col-md-3"><x-form-input name="phone" label="Phone" type="tel" placeholder="+1-555-0000" /></div>
                <div class="col-md-3"><x-form-input name="email" label="Email" type="email" placeholder="student@email.com" /></div>
                <div class="col-md-3"><x-form-input name="aadhar_no" label="Aadhar/SSN No." placeholder="Enter ID number" /></div>
                <div class="col-12"><x-form-textarea name="address" label="Address" rows="2" placeholder="Enter full address" /></div>
                <div class="col-md-4"><x-form-input name="city" label="City" placeholder="City" /></div>
                <div class="col-md-4"><x-form-select name="state" label="State" :options="['CA'=>'California','NY'=>'New York','TX'=>'Texas','FL'=>'Florida']" /></div>
                <div class="col-md-4"><x-form-input name="zipcode" label="Zip Code" placeholder="Zip code" /></div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-users me-2 text-success"></i>Parent / Guardian</h6></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4"><x-form-input name="father_name" label="Father's Name" placeholder="Full name" /></div>
                <div class="col-md-4"><x-form-input name="father_phone" label="Father's Phone" type="tel" /></div>
                <div class="col-md-4"><x-form-input name="father_occupation" label="Father's Occupation" placeholder="Occupation" /></div>
                <div class="col-md-4"><x-form-input name="mother_name" label="Mother's Name" placeholder="Full name" /></div>
                <div class="col-md-4"><x-form-input name="mother_phone" label="Mother's Phone" type="tel" /></div>
                <div class="col-md-4"><x-form-input name="mother_occupation" label="Mother's Occupation" placeholder="Occupation" /></div>
                <div class="col-md-4"><x-form-input name="guardian_name" label="Guardian Name" placeholder="If different" /></div>
                <div class="col-md-4"><x-form-input name="guardian_phone" label="Guardian Phone" type="tel" /></div>
                <div class="col-md-4"><x-form-select name="guardian_relation" label="Relation" :options="['father'=>'Father','mother'=>'Mother','uncle'=>'Uncle','aunt'=>'Aunt','other'=>'Other']" /></div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-school me-2 text-info"></i>Academic Information</h6></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4"><x-form-select name="class" label="Class" required :options="['1'=>'Grade 1','2'=>'Grade 2','3'=>'Grade 3','4'=>'Grade 4','5'=>'Grade 5','6'=>'Grade 6','7'=>'Grade 7','8'=>'Grade 8','9'=>'Grade 9','10'=>'Grade 10','11'=>'Grade 11','12'=>'Grade 12']" /></div>
                <div class="col-md-4"><x-form-select name="section" label="Section" :options="['A'=>'A','B'=>'B','C'=>'C']" /></div>
                <div class="col-md-4"><x-form-select name="house" label="House" :options="['red'=>'Red House','blue'=>'Blue House','green'=>'Green House','yellow'=>'Yellow House']" /></div>
                <div class="col-md-4"><x-form-select name="academic_year" label="Academic Year" :options="['2026'=>'2025-2026','2027'=>'2026-2027']" /></div>
                <div class="col-md-4"><x-form-input name="roll_number" label="Roll Number" placeholder="Auto or manual" /></div>
                <div class="col-md-4"><x-form-select name="admission_type" label="Admission Type" :options="['new'=>'New','transfer'=>'Transfer']" /></div>
                <div class="col-md-6"><x-form-input name="previous_school" label="Previous School" placeholder="If transfer" /></div>
                <div class="col-md-3"><x-form-input name="previous_class" label="Previous Class" placeholder="e.g. Grade 9" /></div>
                <div class="col-md-3"><x-form-input name="admission_date" label="Admission Date" type="date" value="{{ date('Y-m-d') }}" /></div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Student</button>
        <a href="{{ route('student.index') }}" class="btn btn-outline-danger"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection