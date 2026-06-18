@extends('layouts.app')
@section('title', 'Award Details')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-trophy me-2"></i>Award Details</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('student-awards.index') }}">Student Awards</a></li><li class="breadcrumb-item active">#{{ $award->id }}</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6"><label class="fw-semibold text-muted small">Student</label><p class="mb-0">{{ $award->student?->name ?? 'N/A' }}</p></div>
            <div class="col-md-6"><label class="fw-semibold text-muted small">Award Name</label><p class="mb-0">{{ $award->award_name }}</p></div>
            <div class="col-md-6"><label class="fw-semibold text-muted small">Category</label><p class="mb-0">{{ $award->award_category ?? 'N/A' }}</p></div>
            <div class="col-md-6"><label class="fw-semibold text-muted small">Date Awarded</label><p class="mb-0">{{ $award->date_awarded }}</p></div>
            <div class="col-md-6"><label class="fw-semibold text-muted small">Level</label><p class="mb-0">{{ $award->level ?? 'N/A' }}</p></div>
            <div class="col-md-6"><label class="fw-semibold text-muted small">Certificate</label><p class="mb-0">{{ $award->certificate_number ?? 'N/A' }}</p></div>
            @if($award->remarks)<div class="col-12"><label class="fw-semibold text-muted small">Remarks</label><p class="mb-0">{{ $award->remarks }}</p></div>@endif
        </div>
    </div>
    <div class="card-footer"><a href="{{ route('student-awards.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a></div>
</div>
@endsection
