@extends('layouts.app')
@section('title', 'Promotion Details')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-arrow-up me-2"></i>Promotion Details</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('student-promotions.index') }}">Student Promotions</a></li><li class="breadcrumb-item active">#{{ $promotion->id }}</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6"><label class="fw-semibold text-muted small">Student</label><p class="mb-0">{{ $promotion->student?->first_name }} {{ $promotion->student?->last_name ?? 'N/A' }}</p></div>
            <div class="col-md-6"><label class="fw-semibold text-muted small">Promotion Date</label><p class="mb-0">{{ $promotion->promotion_date ? $promotion->promotion_date->format('d-m-Y') : 'N/A' }}</p></div>
            <div class="col-md-6"><label class="fw-semibold text-muted small">From Class</label><p class="mb-0">{{ $promotion->fromClass?->name ?? 'N/A' }}</p></div>
            <div class="col-md-6"><label class="fw-semibold text-muted small">To Class</label><p class="mb-0">{{ $promotion->toClass?->name ?? 'N/A' }}</p></div>
            <div class="col-md-6"><label class="fw-semibold text-muted small">From Section</label><p class="mb-0">{{ $promotion->fromSection?->name ?? 'N/A' }}</p></div>
            <div class="col-md-6"><label class="fw-semibold text-muted small">To Section</label><p class="mb-0">{{ $promotion->toSection?->name ?? 'N/A' }}</p></div>
            <div class="col-md-6"><label class="fw-semibold text-muted small">Status</label><p class="mb-0"><span class="badge bg-{{ $promotion->status === 'completed' ? 'success' : ($promotion->status === 'pending' ? 'warning' : 'secondary') }}">{{ ucfirst($promotion->status ?? 'pending') }}</span></p></div>
            @if($promotion->remarks)<div class="col-12"><label class="fw-semibold text-muted small">Remarks</label><p class="mb-0">{{ $promotion->remarks }}</p></div>@endif
        </div>
    </div>
    <div class="card-footer"><a href="{{ route('student-promotions.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a></div>
</div>
@endsection