@extends('layouts.app')
@section('title', 'Student Profile')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-user-graduate me-2"></i>Student Profile</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li><li class="breadcrumb-item active">John Doe</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('student.edit', 1) }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i>Edit</a>
        <button class="btn btn-outline-success"><i class="fas fa-print me-1"></i>Print Profile</button>
        <button class="btn btn-outline-danger"><i class="fas fa-trash me-1"></i>Delete</button>
    </div>
</div>

<div class="profile-header">
    <div class="d-flex align-items-center gap-4 flex-wrap">
        <div class="profile-avatar bg-primary d-flex align-items-center justify-content-center" style="font-size:2.5rem;font-weight:700">JD</div>
        <div>
            <h3 class="mb-1 fw-bold">John Michael Doe</h3>
            <p class="mb-1 opacity-75"><i class="fas fa-id-card me-2"></i>STU-0001 | ADM-2025001</p>
            <p class="mb-0 opacity-75"><i class="fas fa-graduation-cap me-2"></i>Grade 10-A | Roll No: 15 | Red House</p>
        </div>
        <div class="ms-auto text-end">
            <span class="badge bg-light text-dark fs-6 px-3 py-2">Active</span>
            <p class="mb-0 mt-1 small opacity-75">Admitted: Mar 15, 2025</p>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <div class="avatar-circle bg-primary mx-auto mb-2" style="width:64px;height:64px;font-size:1.5rem">JD</div>
                <h6 class="fw-semibold mb-1">John Doe</h6>
                <p class="text-muted small mb-2">STU-0001</p>
                <hr>
                <div class="text-start small">
                    <div class="d-flex justify-content-between mb-1"><span class="text-muted">Attendance</span><span class="fw-semibold text-success">94%</span></div>
                    <div class="d-flex justify-content-between mb-1"><span class="text-muted">Fees Paid</span><span class="fw-semibold text-success">$2,500</span></div>
                    <div class="d-flex justify-content-between mb-1"><span class="text-muted">Fees Due</span><span class="fw-semibold text-danger">$500</span></div>
                    <div class="d-flex justify-content-between mb-1"><span class="text-muted">Rank</span><span class="fw-semibold">5/42</span></div>
                </div>
                <hr>
                <div class="d-grid gap-2">
                    <button class="btn btn-sm btn-outline-primary"><i class="fas fa-envelope me-1"></i>Send Email</button>
                    <button class="btn btn-sm btn-outline-success"><i class="fas fa-phone me-1"></i>Call Parent</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom py-3">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item"><a class="nav-link active" href="#overview">Overview</a></li>
                    <li class="nav-item"><a class="nav-link" href="#academic">Academics</a></li>
                    <li class="nav-item"><a class="nav-link" href="#fees">Fees</a></li>
                    <li class="nav-item"><a class="nav-link" href="#attendance">Attendance</a></li>
                    <li class="nav-item"><a class="nav-link" href="#documents">Documents</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="overview">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <h6 class="fw-semibold border-bottom pb-2 mb-3"><i class="fas fa-info-circle me-2 text-primary"></i>Personal Info</h6>
                                <div class="mb-2"><small class="text-muted d-block">Date of Birth</small><span class="fw-semibold">Jan 15, 2010</span></div>
                                <div class="mb-2"><small class="text-muted d-block">Gender</small><span class="fw-semibold">Male</span></div>
                                <div class="mb-2"><small class="text-muted d-block">Blood Group</small><span class="fw-semibold">O+</span></div>
                                <div class="mb-2"><small class="text-muted d-block">Nationality</small><span class="fw-semibold">American</span></div>
                                <div class="mb-2"><small class="text-muted d-block">Religion</small><span class="fw-semibold">Christian</span></div>
                                <div class="mb-2"><small class="text-muted d-block">Phone</small><span class="fw-semibold">+1-555-0100</span></div>
                                <div class="mb-2"><small class="text-muted d-block">Email</small><span class="fw-semibold">john.doe@school.edu</span></div>
                                <div class="mb-2"><small class="text-muted d-block">Address</small><span class="fw-semibold">123 Main St, NY 10001</span></div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-semibold border-bottom pb-2 mb-3"><i class="fas fa-users me-2 text-success"></i>Parent Details</h6>
                                <div class="mb-2"><small class="text-muted d-block">Father</small><span class="fw-semibold">Robert Doe</span></div>
                                <div class="mb-2"><small class="text-muted d-block">Father's Phone</small><span class="fw-semibold">+1-555-0200</span></div>
                                <div class="mb-2"><small class="text-muted d-block">Father's Occupation</small><span class="fw-semibold">Engineer</span></div>
                                <div class="mb-2"><small class="text-muted d-block">Mother</small><span class="fw-semibold">Sarah Doe</span></div>
                                <div class="mb-2"><small class="text-muted d-block">Mother's Phone</small><span class="fw-semibold">+1-555-0201</span></div>
                                <div class="mb-2"><small class="text-muted d-block">Guardian</small><span class="fw-semibold">Robert Doe (Father)</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection