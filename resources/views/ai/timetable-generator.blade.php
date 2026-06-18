@extends('layouts.app')
@section('title', 'AI Timetable Generator')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-table me-2"></i>AI Timetable Generator</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">AI</a></li><li class="breadcrumb-item active">Timetable Generator</li></ol></nav>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <x-stats-card icon="fa-school" value="{{ $classes->count() ?? 0 }}" title="Total Classes" color="primary" />
    </div>
    <div class="col-md-4">
        <x-stats-card icon="fa-chalkboard-user" value="{{ $teachers->count() ?? 0 }}" title="Available Teachers" color="success" />
    </div>
    <div class="col-md-4">
        <x-stats-card icon="fa-list" value="{{ $existing ?? 0 }}" title="Existing Timetable Entries" color="info" />
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-wand-magic-sparkles me-2"></i>Generate Timetable</h6></div>
    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-1"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <form method="POST" action="{{ route('ai.timetable-generator.store') }}" class="row g-2 align-items-end">
            @csrf
            <div class="col-md-6">
                <label class="form-label fw-semibold">Select Class</label>
                <select name="class_id" class="form-select" required>
                    <option value="">-- Select Class --</option>
                    @foreach($classes as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-magic me-1"></i>Generate with AI
                </button>
            </div>
        </form>

        <hr>
        <h6 class="fw-semibold mb-2">Available Resources</h6>
        <div class="row g-2">
            <div class="col-md-3">
                <div class="border rounded p-2 text-center">
                    <small class="text-muted">Classes</small>
                    <h5 class="fw-bold mb-0">{{ $classes->count() }}</h5>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-2 text-center">
                    <small class="text-muted">Teachers</small>
                    <h5 class="fw-bold mb-0">{{ $teachers->count() }}</h5>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded p-2 text-center">
                    <small class="text-muted">Existing Entries</small>
                    <h5 class="fw-bold mb-0">{{ $existing }}</h5>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
