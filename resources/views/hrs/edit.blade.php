@extends('layouts.app')
@section('title', 'Edit Employee')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-user-edit me-2"></i>Edit Employee</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('hr.index') }}">HR</a></li><li class="breadcrumb-item"><a href="{{ route('hr.show',1) }}">John Smith</a></li><li class="breadcrumb-item active">Edit</li></ol></nav>
    </div>
</div>
<form>
    @csrf @method('PUT')
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3"><x-form-input name="first_name" label="First Name" value="John" /></div>
                <div class="col-md-3"><x-form-input name="last_name" label="Last Name" value="Smith" /></div>
                <div class="col-md-3"><x-form-input name="employee_id" label="Employee ID" value="EMP-0001" /></div>
                <div class="col-md-3"><x-form-input name="phone" label="Phone" value="+1-555-2001" /></div>
                <div class="col-md-4"><x-form-input name="email" label="Email" value="john.smith@school.edu" /></div>
                <div class="col-md-4"><x-form-select name="department" label="Department" :options="['teaching'=>'Teaching']" value="teaching" /></div>
                <div class="col-md-4"><x-form-select name="designation" label="Designation" :options="['senior_teacher'=>'Senior Teacher']" value="senior_teacher" /></div>
                <div class="col-md-4"><x-form-select name="employment_type" label="Type" :options="['permanent'=>'Permanent']" value="permanent" /></div>
                <div class="col-md-4"><x-form-input name="joining_date" label="Joining Date" type="date" value="2020-03-01" /></div>
                <div class="col-md-4"><x-form-input name="experience" label="Experience (years)" type="number" value="12" /></div>
                <div class="col-md-4"><x-form-select name="status" label="Status" :options="['active'=>'Active','inactive'=>'Inactive']" value="active" /></div>
                <div class="col-12"><x-form-textarea name="address" label="Address" rows="2">456 Oak Avenue, New York</x-form-textarea></div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
        <a href="{{ route('hr.show',1) }}" class="btn btn-outline-secondary"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection