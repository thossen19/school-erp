@extends('layouts.app')
@section('title', 'Exam Results')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-poll me-2"></i>Exam Results</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('assessment.index') }}">Assessments</a></li><li class="breadcrumb-item active">Results</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addResultModal"><i class="fas fa-plus me-1"></i>Add Result</button>
        <button class="btn btn-outline-success"><i class="fas fa-file-excel me-1"></i>Export</button>
    </div>
</div>

<div class="filter-bar">
    <form method="GET" action="{{ route('assessment.results') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Exam</label>
                <select name="exam_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Exams</option>
                    @foreach($exams as $exam)
                        <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>{{ $exam->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Subject</label>
                <select name="subject_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Status</label>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="passed" {{ request('status') == 'passed' ? 'selected' : '' }}>Passed</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Search Student</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Name or admission no..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><x-stats-card title="Total Results" value="{{ $stats['total'] }}" icon="fa-poll" color="primary" /></div>
    <div class="col-md-3"><x-stats-card title="Passed" value="{{ $stats['passed'] }}" icon="fa-check-circle" color="success" trend="{{ $stats['total'] > 0 ? round($stats['passed']/$stats['total']*100, 1) : 0 }}% pass rate" :trendUp="true" /></div>
    <div class="col-md-3"><x-stats-card title="Failed" value="{{ $stats['failed'] }}" icon="fa-times-circle" color="danger" /></div>
    <div class="col-md-3"><x-stats-card title="Average" value="{{ $stats['avg_percentage'] }}%" icon="fa-chart-line" color="info" /></div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Student','Roll','Exam','Subject','Marks','Total','Percentage','Grade','Status']">
            @forelse($results as $r)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><a href="#" class="text-decoration-none">{{ $r->student?->first_name }} {{ $r->student?->last_name }}</a></td>
                    <td>{{ $r->student?->admission_no ?? '-' }}</td>
                    <td>{{ $r->exam?->name ?? '-' }}</td>
                    <td>{{ $r->subject?->name ?? '-' }}</td>
                    <td class="fw-bold">{{ $r->marks_obtained }}</td>
                    <td>{{ $r->total_marks }}</td>
                    <td>{{ $r->percentage }}%</td>
                    <td><span class="badge bg-{{ $r->percentage >= 75 ? 'success' : ($r->percentage >= 50 ? 'warning' : 'danger') }}">{{ $r->grade }}</span></td>
                    <td><span class="badge bg-{{ $r->status == 'passed' ? 'success' : 'danger' }}">{{ ucfirst($r->status) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="10" class="text-center text-muted py-3">No records found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$results" />
</div>

<x-modal id="addResultModal" title="Add Exam Result">
    <form>
        <x-form-select name="exam" label="Exam" :options="['1'=>'Midterm Exam']" />
        <x-form-select name="student" label="Student" :options="['1'=>'John Doe','2'=>'Jane Smith']" />
        <x-form-input name="marks_obtained" label="Marks Obtained" type="number" />
        <x-form-input name="total_marks" label="Total Marks" type="number" value="100" />
        <x-form-textarea name="remarks" label="Remarks" rows="2" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection
