@extends('layouts.app')
@section('title', 'Student Portal')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-user-graduate me-2"></i>Student Portal</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item active">Student Portal</li>
            </ol>
        </nav>
    </div>
</div>

@if(!$student)
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>No student profile is linked to your account.
    </div>
@else
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <div class="avatar-circle mx-auto mb-3" style="width:80px;height:80px;font-size:2rem;line-height:80px;background:linear-gradient(135deg,#667eea,#764ba2)">{{ substr($student->first_name, 0, 1) }}</div>
                    <h5 class="fw-bold">{{ $student->full_name }}</h5>
                    <p class="text-muted small mb-1">{{ $student->class?->name ?? '-' }} @if($student->section) - {{ $student->section->name }} @endif</p>
                    <p class="text-muted small">Roll: {{ $student->roll_number ?? '-' }} | ID: {{ $student->admission_no ?? '-' }}</p>
                    <hr>
                    <div class="text-start small">
                        <div class="d-flex justify-content-between mb-1"><span class="text-muted">Email</span><span>{{ $student->email ?? '-' }}</span></div>
                        <div class="d-flex justify-content-between mb-1"><span class="text-muted">Phone</span><span>{{ $student->phone ?? '-' }}</span></div>
                        <div class="d-flex justify-content-between mb-1"><span class="text-muted">Gender</span><span>{{ ucfirst($student->gender ?? '-') }}</span></div>
                        <div class="d-flex justify-content-between mb-1"><span class="text-muted">DOB</span><span>{{ $student->date_of_birth?->format('d M Y') ?? '-' }}</span></div>
                        <div class="d-flex justify-content-between mb-1"><span class="text-muted">Blood Group</span><span>{{ $student->blood_group ?? '-' }}</span></div>
                        <div class="d-flex justify-content-between"><span class="text-muted">House</span><span>{{ $student->house?->name ?? '-' }}</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2">
                    <h6 class="fw-semibold mb-0"><i class="fas fa-graduation-cap me-2 text-primary"></i>Academic Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-bordered mb-0">
                        <tr><th class="bg-light" style="width:160px">Academic Year</th><td>{{ $student->academicYear?->name ?? '-' }}</td></tr>
                        <tr><th class="bg-light">Class</th><td>{{ $student->class?->name ?? '-' }}</td></tr>
                        <tr><th class="bg-light">Section</th><td>{{ $student->section?->name ?? '-' }}</td></tr>
                        <tr><th class="bg-light">Admission Date</th><td>{{ $student->admission_date?->format('d M Y') ?? '-' }}</td></tr>
                        <tr><th class="bg-light">Admission No</th><td>{{ $student->admission_no ?? '-' }}</td></tr>
                        <tr><th class="bg-light">Roll Number</th><td>{{ $student->roll_number ?? '-' }}</td></tr>
                        <tr><th class="bg-light">Status</th><td><span class="badge bg-{{ $student->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($student->status ?? 'active') }}</span></td></tr>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white py-2">
                    <h6 class="fw-semibold mb-0"><i class="fas fa-address-book me-2 text-success"></i>Contact & Address</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-bordered mb-0">
                        <tr><th class="bg-light" style="width:160px">Address</th><td>{{ $student->address ?? '-' }}</td></tr>
                        <tr><th class="bg-light">City</th><td>{{ $student->city ?? '-' }}</td></tr>
                        <tr><th class="bg-light">State</th><td>{{ $student->state ?? '-' }}</td></tr>
                        <tr><th class="bg-light">Country</th><td>{{ $student->country ?? '-' }}</td></tr>
                        <tr><th class="bg-light">Pincode</th><td>{{ $student->pincode ?? '-' }}</td></tr>
                    </table>
                </div>
            </div>

            @if($student->parents->count())
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-2">
                        <h6 class="fw-semibold mb-0"><i class="fas fa-users me-2 text-warning"></i>Parents / Guardians</h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead class="table-light"><tr><th>Name</th><th>Relationship</th><th>Phone</th><th>Email</th></tr></thead>
                            <tbody>
                                @foreach($student->parents as $parent)
                                    <tr>
                                        <td class="fw-semibold">{{ $parent->full_name }}</td>
                                        <td>{{ ucfirst($parent->pivot->relationship ?? '-') }}</td>
                                        <td>{{ $parent->phone ?? '-' }}</td>
                                        <td>{{ $parent->email ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif
@endsection
