@extends('layouts.app')
@section('title', 'Alumni Profile')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-user-graduate me-2"></i>Alumni Profile</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('alumni.index') }}">Alumni</a></li><li class="breadcrumb-item active">Alice Johnson</li></ol></nav>
    </div>
    <div class="d-flex gap-2"><a href="{{ route('alumni.edit',1) }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i>Edit</a></div>
</div>
<div class="profile-header" style="background:linear-gradient(135deg,#198754,#0dcaf0)">
    <div class="d-flex align-items-center gap-4">
        <div class="profile-avatar bg-light text-dark d-flex align-items-center justify-content-center" style="font-size:2.5rem;font-weight:700">AJ</div>
        <div><h3 class="mb-1 fw-bold">Alice Johnson</h3><p class="mb-0 opacity-75">Class of 2022 | Grade 12</p></div>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4"><small class="text-muted d-block">Occupation</small><span class="fw-semibold">Software Engineer</span></div>
            <div class="col-md-4"><small class="text-muted d-block">Company</small><span>Tech Corp</span></div>
            <div class="col-md-4"><small class="text-muted d-block">Phone</small><span>+1-555-11001</span></div>
            <div class="col-md-4"><small class="text-muted d-block">Email</small><span>alice.j@email.com</span></div>
            <div class="col-md-4"><small class="text-muted d-block">LinkedIn</small><a href="#">linkedin.com/in/alicejohnson</a></div>
            <div class="col-12"><small class="text-muted d-block">Address</small><span>456 Tech Park, Silicon Valley, CA</span></div>
        </div>
    </div>
</div>
@endsection