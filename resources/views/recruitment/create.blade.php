@extends('layouts.app')
@section('title', 'Create Job Posting')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-plus-circle me-2"></i>Create Job Posting</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('hr.recruitment') }}">Recruitment</a></li><li class="breadcrumb-item active">Create</li></ol></nav>
    </div>
</div>

<form action="{{ route('hr.recruitment.store') }}" method="POST">
    @csrf
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-briefcase me-2 text-primary"></i>Job Details</h6></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <x-form-input name="job_title" label="Job Title" required value="{{ old('job_title') }}" placeholder="e.g. Senior Math Teacher" />
                </div>
                <div class="col-md-3">
                    <x-form-input name="vacancies" label="Vacancies" type="number" required value="{{ old('vacancies', 1) }}" />
                </div>
                <div class="col-md-3">
                    <x-form-input name="salary_range" label="Salary Range" value="{{ old('salary_range') }}" placeholder="e.g. 50000-80000" />
                </div>
                <div class="col-md-4">
                    <x-form-select name="department_id" label="Department" required :options="$departments->pluck('name', 'id')->toArray()" />
                </div>
                <div class="col-md-4">
                    <x-form-select name="designation_id" label="Designation" required :options="$designations->pluck('name', 'id')->toArray()" />
                </div>
                <div class="col-md-4">
                    <x-form-select name="status" label="Status" required :options="['draft'=>'Draft','open'=>'Open','closed'=>'Closed']" />
                </div>
                <div class="col-md-4">
                    <x-form-input name="posted_date" label="Posted Date" type="date" required value="{{ old('posted_date', now()->format('Y-m-d')) }}" />
                </div>
                <div class="col-md-4">
                    <x-form-input name="closing_date" label="Closing Date" type="date" required value="{{ old('closing_date', now()->addDays(30)->format('Y-m-d')) }}" />
                </div>
                <div class="col-12">
                    <x-form-textarea name="description" label="Job Description" rows="4" placeholder="Describe the role and responsibilities">{{ old('description') }}</x-form-textarea>
                </div>
                <div class="col-12">
                    <x-form-textarea name="requirements" label="Requirements" rows="4" placeholder="List qualifications, skills, and experience required">{{ old('requirements') }}</x-form-textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Create Posting</button>
        <a href="{{ route('hr.recruitment') }}" class="btn btn-outline-danger"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection
