@extends('layouts.app')
@section('title', 'Discipline Record Details')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-exclamation-triangle me-2"></i>Discipline Record Details</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('student-disciplines.index') }}">Student Disciplines</a></li><li class="breadcrumb-item active">#{{ $record->id }}</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6"><label class="fw-semibold text-muted small">Student</label><p class="mb-0">{{ $record->student?->first_name }} {{ $record->student?->last_name }}</p></div>
            <div class="col-md-6"><label class="fw-semibold text-muted small">Incident Date</label><p class="mb-0">{{ $record->incident_date->format('d-m-Y') }}</p></div>
            <div class="col-md-6"><label class="fw-semibold text-muted small">Incident Type</label><p class="mb-0">{{ $record->incident_type }}</p></div>
            <div class="col-md-6"><label class="fw-semibold text-muted small">Status</label><p class="mb-0"><span class="badge bg-{{ $record->status === 'resolved' ? 'success' : ($record->status === 'pending' ? 'warning' : 'secondary') }}">{{ ucfirst($record->status) }}</span></p></div>
            @if($record->description)<div class="col-12"><label class="fw-semibold text-muted small">Description</label><p class="mb-0">{{ $record->description }}</p></div>@endif
            @if($record->action_taken)<div class="col-12"><label class="fw-semibold text-muted small">Action Taken</label><p class="mb-0">{{ $record->action_taken }}</p></div>@endif
        </div>
    </div>
    <div class="card-footer"><a href="{{ route('student-disciplines.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a></div>
</div>
@endsection
