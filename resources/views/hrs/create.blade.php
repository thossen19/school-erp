@extends('layouts.app')
@section('title', 'Add Employee')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-user-plus me-2"></i>Add Employee</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('hr.index') }}">HR</a></li><li class="breadcrumb-item active">Add Employee</li></ol></nav>
    </div>
</div>
<form>
    @csrf
    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-user me-2 text-primary"></i>Personal Information</h6></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3"><x-form-input name="first_name" label="First Name" required /></div>
                <div class="col-md-3"><x-form-input name="last_name" label="Last Name" required /></div>
                <div class="col-md-3"><x-form-input name="employee_id" label="Employee ID" required placeholder="EMP-0001" /></div>
                <div class="col-md-3"><x-form-input name="date_of_birth" label="Date of Birth" type="date" /></div>
                <div class="col-md-3"><x-form-select name="gender" label="Gender" :options="['male'=>'Male','female'=>'Female','other'=>'Other']" /></div>
                <div class="col-md-3"><x-form-input name="phone" label="Phone" type="tel" /></div>
                <div class="col-md-3"><x-form-input name="email" label="Email" type="email" /></div>
                <div class="col-md-3"><x-form-input name="blood_group" label="Blood Group" /></div>
                <div class="col-12"><x-form-textarea name="address" label="Address" rows="2" /></div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-briefcase me-2 text-success"></i>Employment Details</h6></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4"><x-form-select name="department" label="Department" :options="['teaching'=>'Teaching','admin'=>'Administration','accounts'=>'Accounts','library'=>'Library','sports'=>'Sports','it'=>'IT']" /></div>
                <div class="col-md-4"><x-form-select name="designation" label="Designation" :options="['teacher'=>'Teacher','senior_teacher'=>'Senior Teacher','principal'=>'Principal','accountant'=>'Accountant','librarian'=>'Librarian','it_admin'=>'IT Admin']" /></div>
                <div class="col-md-4"><x-form-select name="employment_type" label="Type" :options="['permanent'=>'Permanent','contract'=>'Contract','probation'=>'Probation','temporary'=>'Temporary']" /></div>
                <div class="col-md-4"><x-form-input name="joining_date" label="Joining Date" type="date" /></div>
                <div class="col-md-4"><x-form-select name="qualification" label="Qualification" :options="['phd'=>'PhD','masters'=>'Masters','bachelors'=>'Bachelors','diploma'=>'Diploma']" /></div>
                <div class="col-md-4"><x-form-input name="experience" label="Experience (years)" type="number" /></div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-file-alt me-2 text-info"></i>Documents</h6></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4"><label class="form-label">Resume/CV</label><input type="file" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">ID Proof</label><input type="file" class="form-control"></div>
                <div class="col-md-4"><label class="form-label">Qualification Certificates</label><input type="file" class="form-control"></div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Employee</button>
        <a href="{{ route('hr.index') }}" class="btn btn-outline-danger"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection