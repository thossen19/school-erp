@extends('layouts.app')
@section('title', 'Employee Profile')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-user-tie me-2"></i>Employee Profile</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('hr.index') }}">HR</a></li><li class="breadcrumb-item active">John Smith</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('hr.edit',1) }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i>Edit</a>
        <button class="btn btn-outline-info"><i class="fas fa-print me-1"></i>Print</button>
    </div>
</div>

<div class="profile-header" style="background:linear-gradient(135deg,#6f42c1,#0d6efd)">
    <div class="d-flex align-items-center gap-4 flex-wrap">
        <div class="profile-avatar bg-light text-dark d-flex align-items-center justify-content-center" style="font-size:2.5rem;font-weight:700">JS</div>
        <div>
            <h3 class="mb-1 fw-bold">John Smith</h3>
            <p class="mb-1 opacity-75"><i class="fas fa-id-card me-2"></i>EMP-0001 | Senior Teacher</p>
            <p class="mb-0 opacity-75"><i class="fas fa-building me-2"></i>Teaching Department</p>
        </div>
        <div class="ms-auto text-end">
            <span class="badge bg-light text-dark fs-6 px-3 py-2">Active</span>
            <p class="mb-0 mt-1 small opacity-75">Joined: Mar 1, 2020</p>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-info me-2 text-primary"></i>Personal Info</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><small class="text-muted d-block">Full Name</small><span class="fw-semibold">John Michael Smith</span></div>
                    <div class="col-md-6"><small class="text-muted d-block">Date of Birth</small><span class="fw-semibold">Jan 15, 1985</span></div>
                    <div class="col-md-6"><small class="text-muted d-block">Gender</small><span class="fw-semibold">Male</span></div>
                    <div class="col-md-6"><small class="text-muted d-block">Blood Group</small><span class="fw-semibold">A+</span></div>
                    <div class="col-md-6"><small class="text-muted d-block">Phone</small><span class="fw-semibold">+1-555-2001</span></div>
                    <div class="col-md-6"><small class="text-muted d-block">Email</small><span class="fw-semibold">john.smith@school.edu</span></div>
                    <div class="col-12"><small class="text-muted d-block">Address</small><span class="fw-semibold">456 Oak Avenue, New York, NY 10002</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-briefcase me-2 text-success"></i>Employment Info</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><small class="text-muted d-block">Department</small><span class="fw-semibold">Teaching</span></div>
                    <div class="col-md-6"><small class="text-muted d-block">Designation</small><span class="fw-semibold">Senior Teacher</span></div>
                    <div class="col-md-6"><small class="text-muted d-block">Employment Type</small><span class="fw-semibold">Permanent</span></div>
                    <div class="col-md-6"><small class="text-muted d-block">Joining Date</small><span class="fw-semibold">Mar 1, 2020</span></div>
                    <div class="col-md-6"><small class="text-muted d-block">Qualification</small><span class="fw-semibold">Masters in Education</span></div>
                    <div class="col-md-6"><small class="text-muted d-block">Experience</small><span class="fw-semibold">12 years</span></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection