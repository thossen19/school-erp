@extends('layouts.app')
@section('title', 'Edit Assessment')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-edit me-2"></i>Edit Assessment</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('assessment.index') }}">Assessments</a></li><li class="breadcrumb-item"><a href="{{ route('assessment.show', $exam->id) }}">{{ $exam->name }}</a></li><li class="breadcrumb-item active">Edit</li></ol></nav>
    </div>
</div>
<form action="{{ route('assessment.update', $exam->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-file-alt me-2 text-primary"></i>Exam Details</h6></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <x-form-input name="name" label="Exam Name" required value="{{ old('name', $exam->name) }}" />
                </div>
                <div class="col-md-4">
                    <x-form-select name="exam_type_id" label="Exam Type" required :options="$examTypes->pluck('name', 'id')->toArray()" :selected="$exam->exam_type_id" />
                </div>
                <div class="col-md-4">
                    <x-form-select name="class_id" label="Class" :options="$classes->pluck('name', 'id')->toArray()" :selected="$exam->class_id" />
                </div>
                <div class="col-md-4">
                    <x-form-input name="start_date" label="Start Date" type="date" required value="{{ old('start_date', $exam->start_date?->format('Y-m-d')) }}" />
                </div>
                <div class="col-md-4">
                    <x-form-input name="end_date" label="End Date" type="date" required value="{{ old('end_date', $exam->end_date?->format('Y-m-d')) }}" />
                </div>
                <div class="col-12">
                    <x-form-textarea name="description" label="Description" rows="3">{{ old('description', $exam->description) }}</x-form-textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
        <a href="{{ route('assessment.show', $exam->id) }}" class="btn btn-outline-secondary"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection
