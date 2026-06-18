@extends('layouts.app')
@section('title', 'Attendance Detail')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-calendar-check me-2"></i>Attendance Detail</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('attendance.index') }}">Attendance</a></li><li class="breadcrumb-item active">#{{ $record->id }}</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="fw-semibold text-muted small">Student</label>
                <p class="mb-0">{{ $record->first_name ?? '' }} {{ $record->last_name ?? '' }}</p>
            </div>
            <div class="col-md-3">
                <label class="fw-semibold text-muted small">Date</label>
                <p class="mb-0">{{ $record->date }}</p>
            </div>
            <div class="col-md-3">
                <label class="fw-semibold text-muted small">Status</label>
                <p class="mb-0">
                    <span class="badge bg-{{ $record->status === 'present' ? 'success' : ($record->status === 'absent' ? 'danger' : ($record->status === 'late' ? 'warning' : 'info')) }}">
                        {{ ucfirst($record->status) }}
                    </span>
                </p>
            </div>
            <div class="col-md-4">
                <label class="fw-semibold text-muted small">Class</label>
                <p class="mb-0">{{ $record->class_name ?? $record->class_id ?? 'N/A' }}</p>
            </div>
            <div class="col-md-4">
                <label class="fw-semibold text-muted small">Section</label>
                <p class="mb-0">{{ $record->section_name ?? $record->section_id ?? 'N/A' }}</p>
            </div>
            <div class="col-md-4">
                <label class="fw-semibold text-muted small">Attendance Type</label>
                <p class="mb-0">{{ $record->attendance_type ?? 'Regular' }}</p>
            </div>
            @if($record->time_in)
            <div class="col-md-3">
                <label class="fw-semibold text-muted small">Time In</label>
                <p class="mb-0">{{ $record->time_in }}</p>
            </div>
            @endif
            @if($record->time_out)
            <div class="col-md-3">
                <label class="fw-semibold text-muted small">Time Out</label>
                <p class="mb-0">{{ $record->time_out }}</p>
            </div>
            @endif
            @if($record->subject_id)
            <div class="col-md-3">
                <label class="fw-semibold text-muted small">Subject</label>
                <p class="mb-0">{{ $record->subject_id }}</p>
            </div>
            @endif
            @if($record->period_id)
            <div class="col-md-3">
                <label class="fw-semibold text-muted small">Period</label>
                <p class="mb-0">{{ $record->period_id }}</p>
            </div>
            @endif
            @if($record->reason)
            <div class="col-12">
                <label class="fw-semibold text-muted small">Reason</label>
                <p class="mb-0">{{ $record->reason }}</p>
            </div>
            @endif
            @if($record->remarks)
            <div class="col-12">
                <label class="fw-semibold text-muted small">Remarks</label>
                <p class="mb-0">{{ $record->remarks }}</p>
            </div>
            @endif
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
    </div>
</div>
@endsection