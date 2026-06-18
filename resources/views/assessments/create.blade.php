@extends('layouts.app')
@section('title', 'Create Assessment')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-plus-circle me-2"></i>Create Assessment</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('assessment.index') }}">Assessments</a></li><li class="breadcrumb-item active">Create</li></ol></nav>
    </div>
</div>
<form action="{{ route('assessment.store') }}" method="POST">
    @csrf
    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-file-alt me-2 text-primary"></i>Exam Details</h6></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <x-form-input name="name" label="Exam Name" required placeholder="e.g. Midterm Exam" value="{{ old('name') }}" />
                </div>
                <div class="col-md-4">
                    <x-form-select name="exam_type_id" label="Exam Type" required :options="$examTypes->pluck('name', 'id')->toArray()" />
                </div>
                <div class="col-md-4">
                    <x-form-select name="class_id" label="Class" :options="$classes->pluck('name', 'id')->toArray()" />
                </div>
                <div class="col-md-4">
                    <x-form-input name="start_date" label="Start Date" type="date" required value="{{ old('start_date') }}" />
                </div>
                <div class="col-md-4">
                    <x-form-input name="end_date" label="End Date" type="date" required value="{{ old('end_date') }}" />
                </div>
                <div class="col-12">
                    <x-form-textarea name="description" label="Description" rows="3" placeholder="Exam description">{{ old('description') }}</x-form-textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Create Assessment</button>
        <a href="{{ route('assessment.index') }}" class="btn btn-outline-danger"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection
