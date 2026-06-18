@extends('layouts.app')
@section('title', 'Document Details')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-file me-2"></i>Document Details</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('student-documents.index') }}">Student Documents</a></li><li class="breadcrumb-item active">#{{ $document->id }}</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6"><label class="fw-semibold text-muted small">Student</label><p class="mb-0">{{ $document->student?->first_name }} {{ $document->student?->last_name ?? 'N/A' }}</p></div>
            <div class="col-md-6"><label class="fw-semibold text-muted small">Document Type</label><p class="mb-0">{{ $document->document_type }}</p></div>
            <div class="col-md-6"><label class="fw-semibold text-muted small">Document Name</label><p class="mb-0">{{ $document->document_name }}</p></div>
            <div class="col-md-6"><label class="fw-semibold text-muted small">File Size</label><p class="mb-0">{{ $document->file_size ? number_format($document->file_size / 1024, 1) . ' KB' : 'N/A' }}</p></div>
            <div class="col-md-6"><label class="fw-semibold text-muted small">MIME Type</label><p class="mb-0">{{ $document->mime_type ?? 'N/A' }}</p></div>
            <div class="col-md-6"><label class="fw-semibold text-muted small">Verified</label><p class="mb-0">{!! $document->is_verified ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-warning">No</span>' !!}</p></div>
            @if($document->expiry_date)<div class="col-md-6"><label class="fw-semibold text-muted small">Expiry Date</label><p class="mb-0">{{ $document->expiry_date->format('d-m-Y') }}</p></div>@endif
            @if($document->remarks)<div class="col-12"><label class="fw-semibold text-muted small">Remarks</label><p class="mb-0">{{ $document->remarks }}</p></div>@endif
        </div>
    </div>
    <div class="card-footer"><a href="{{ route('student-documents.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a></div>
</div>
@endsection