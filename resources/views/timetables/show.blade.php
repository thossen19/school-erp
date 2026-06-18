@extends('layouts.app')

@section('title', 'Timetable Details')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="fw-bold mb-0">Timetable Details</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route_if_exists('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route_if_exists('timetables.index') }}">Timetables</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @if(isset($timetable) && $timetable)
                <a href="{{ route('timetables.edit', $timetable->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit me-1"></i>Edit</a>
            @endif
            <a href="{{ route('timetables.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i>Back</a>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <p class="text-muted">Timetable details view.</p>
    </div>
</div>
@endsection
