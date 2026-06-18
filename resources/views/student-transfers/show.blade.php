@extends('layouts.app')
@section('title', 'Transfer Details')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-exchange-alt me-2"></i>Transfer Details</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('student-transfers.index') }}">Student Transfers</a></li><li class="breadcrumb-item active">#{{ $transfer->id }}</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6"><label class="fw-semibold text-muted small">Student</label><p class="mb-0">{{ $transfer->student?->first_name }} {{ $transfer->student?->last_name ?? 'N/A' }}</p></div>
            <div class="col-md-6"><label class="fw-semibold text-muted small">Transfer Date</label><p class="mb-0">{{ $transfer->transfer_date ? $transfer->transfer_date->format('d-m-Y') : 'N/A' }}</p></div>
            <div class="col-md-6"><label class="fw-semibold text-muted small">From Class</label><p class="mb-0">{{ $transfer->fromClass?->name ?? 'N/A' }}</p></div>
            <div class="col-md-6"><label class="fw-semibold text-muted small">To Class</label><p class="mb-0">{{ $transfer->toClass?->name ?? 'N/A' }}</p></div>
            <div class="col-md-6"><label class="fw-semibold text-muted small">Transfer Certificate No.</label><p class="mb-0">{{ $transfer->transfer_certificate_no ?? 'N/A' }}</p></div>
            <div class="col-md-6"><label class="fw-semibold text-muted small">Status</label><p class="mb-0"><span class="badge bg-{{ $transfer->status === 'completed' ? 'success' : ($transfer->status === 'pending' ? 'warning' : 'secondary') }}">{{ ucfirst($transfer->status ?? 'pending') }}</span></p></div>
            @if($transfer->transfer_reason)<div class="col-12"><label class="fw-semibold text-muted small">Reason</label><p class="mb-0">{{ $transfer->transfer_reason }}</p></div>@endif
            @if($transfer->remarks)<div class="col-12"><label class="fw-semibold text-muted small">Remarks</label><p class="mb-0">{{ $transfer->remarks }}</p></div>@endif
        </div>
    </div>
    <div class="card-footer"><a href="{{ route('student-transfers.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a></div>
</div>
@endsection