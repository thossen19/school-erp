@extends('layouts.app')
@section('title', 'Continuous Assessment')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chart-line me-2"></i>Continuous Assessment</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('assessment.index') }}">Assessments</a></li><li class="breadcrumb-item active">Continuous Assessment</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCAModal"><i class="fas fa-plus me-1"></i>Add Assessment</button>
</div>

<div class="filter-bar">
    <form method="GET" action="{{ route('assessment.continuous') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Subject</label>
                <select name="subject_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Assessment Type</label>
                <select name="assessment_type" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Types</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('assessment_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search Student</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Student name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Student','Subject','Title','Type','Marks','Total','Percentage','Date','Grade']">
            @forelse($assessments as $ca)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><a href="#" class="text-decoration-none">{{ $ca->student?->first_name }} {{ $ca->student?->last_name }}</a></td>
                    <td>{{ $ca->subject?->name ?? '-' }}</td>
                    <td>{{ $ca->title }}</td>
                    <td><span class="badge bg-info">{{ $ca->assessment_type }}</span></td>
                    <td class="fw-bold">{{ $ca->marks_obtained }}</td>
                    <td>{{ $ca->max_marks }}</td>
                    <td>{{ $ca->percentage }}%</td>
                    <td>{{ $ca->assessment_date?->format('d-m-Y') ?? '-' }}</td>
                    <td><span class="badge bg-{{ $ca->percentage && $ca->percentage >= 75 ? 'success' : ($ca->percentage >= 50 ? 'warning' : 'danger') }}">{{ $ca->grade ?? '-' }}</span></td>
                </tr>
            @empty
                <tr><td colspan="10" class="text-center text-muted py-3">No continuous assessments found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$assessments" />
</div>

<x-modal id="addCAModal" title="Add Continuous Assessment">
    <form>
        <x-form-select name="student" label="Student" :options="['1'=>'John Doe']" />
        <x-form-select name="subject" label="Subject" :options="['math'=>'Mathematics','eng'=>'English']" />
        <x-form-input name="assignment" label="Assignment (20)" type="number" />
        <x-form-input name="quiz" label="Quiz (20)" type="number" />
        <x-form-input name="project" label="Project (20)" type="number" />
        <x-form-input name="participation" label="Participation (10)" type="number" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection
