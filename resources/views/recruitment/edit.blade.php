@extends('layouts.app')
@section('title', 'Edit Job Posting')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-edit me-2"></i>Edit Job Posting</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('hr.recruitment') }}">Recruitment</a></li><li class="breadcrumb-item"><a href="{{ route('hr.recruitment.show', $posting->id) }}">{{ $posting->job_title }}</a></li><li class="breadcrumb-item active">Edit</li></ol></nav>
    </div>
</div>

<form action="{{ route('hr.recruitment.update', $posting->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-briefcase me-2 text-primary"></i>Job Details</h6></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <x-form-input name="job_title" label="Job Title" required value="{{ old('job_title', $posting->job_title) }}" />
                </div>
                <div class="col-md-3">
                    <x-form-input name="vacancies" label="Vacancies" type="number" required value="{{ old('vacancies', $posting->vacancies) }}" />
                </div>
                <div class="col-md-3">
                    <x-form-input name="salary_range" label="Salary Range" value="{{ old('salary_range', $posting->salary_range) }}" />
                </div>
                <div class="col-md-4">
                    <x-form-select name="department_id" label="Department" required :options="$departments->pluck('name', 'id')->toArray()" :selected="$posting->department_id" />
                </div>
                <div class="col-md-4">
                    <x-form-select name="designation_id" label="Designation" required :options="$designations->pluck('name', 'id')->toArray()" :selected="$posting->designation_id" />
                </div>
                <div class="col-md-4">
                    <x-form-select name="status" label="Status" required :options="['draft'=>'Draft','open'=>'Open','closed'=>'Closed']" :selected="$posting->status" />
                </div>
                <div class="col-md-4">
                    <x-form-input name="posted_date" label="Posted Date" type="date" required value="{{ old('posted_date', $posting->posted_date) }}" />
                </div>
                <div class="col-md-4">
                    <x-form-input name="closing_date" label="Closing Date" type="date" required value="{{ old('closing_date', $posting->closing_date) }}" />
                </div>
                <div class="col-12">
                    <x-form-textarea name="description" label="Job Description" rows="4">{{ old('description', $posting->description) }}</x-form-textarea>
                </div>
                <div class="col-12">
                    <x-form-textarea name="requirements" label="Requirements" rows="4">{{ old('requirements', $posting->requirements) }}</x-form-textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update Posting</button>
        <a href="{{ route('hr.recruitment.show', $posting->id) }}" class="btn btn-outline-secondary"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection
