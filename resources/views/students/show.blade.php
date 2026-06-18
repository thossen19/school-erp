@extends('layouts.app')
@section('title', 'Student Details')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-user-graduate me-2"></i>Student Details</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Students</a></li>
                <li class="breadcrumb-item active">{{ $student->admission_no ?? 'Details' }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i>Edit</a>
        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
    </div>
</div>
<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body">
                <div class="mb-3">
                    @if($student->photo)
                        <img src="{{ asset('storage/' . $student->photo) }}" class="rounded-circle" width="120" height="120" style="object-fit:cover">
                    @else
                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width:120px;height:120px">
                            <i class="fas fa-user-graduate fa-4x text-muted"></i>
                        </div>
                    @endif
                </div>
                <h5 class="fw-semibold">{{ $student->first_name }} {{ $student->last_name }}</h5>
                <p class="text-muted mb-1">{{ $student->admission_no }}</p>
                <span class="badge bg-{{ $student->status === 'active' ? 'success' : 'danger' }}">{{ ucfirst($student->status ?? 'active') }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent"><h6 class="fw-semibold mb-0">Personal Information</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6"><label class="fw-semibold small">Date of Birth</label><p class="mb-0">{{ $student->date_of_birth ?? '-' }}</p></div>
                    <div class="col-sm-6"><label class="fw-semibold small">Gender</label><p class="mb-0">{{ ucfirst($student->gender ?? '-') }}</p></div>
                    <div class="col-sm-6"><label class="fw-semibold small">Class</label><p class="mb-0">{{ $student->class->name ?? '-' }}</p></div>
                    <div class="col-sm-6"><label class="fw-semibold small">Section</label><p class="mb-0">{{ $student->section->name ?? '-' }}</p></div>
                    <div class="col-sm-6"><label class="fw-semibold small">Phone</label><p class="mb-0">{{ $student->phone ?? '-' }}</p></div>
                    <div class="col-sm-6"><label class="fw-semibold small">Email</label><p class="mb-0">{{ $student->email ?? '-' }}</p></div>
                    <div class="col-sm-6"><label class="fw-semibold small">House</label><p class="mb-0">{{ $student->house->name ?? '-' }}</p></div>
                    <div class="col-sm-6"><label class="fw-semibold small">Admission Date</label><p class="mb-0">{{ $student->created_at ? $student->created_at->format('d M Y') : '-' }}</p></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection