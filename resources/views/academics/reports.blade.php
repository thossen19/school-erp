@extends('layouts.app')
@section('title', 'Academic Reports')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chart-bar me-2"></i>Academic Reports</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('academic.index') }}">Academic</a></li><li class="breadcrumb-item active">Reports</li></ol></nav>
    </div>
</div>

<div class="row g-2 mb-3">
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-primary">{{ $totalClasses }}</h5><small class="text-muted">Classes</small></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-info">{{ $totalSections }}</h5><small class="text-muted">Sections</small></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-success">{{ $totalSubjects }}</h5><small class="text-muted">Subjects</small></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-warning">{{ $totalStudents }}</h5><small class="text-muted">Students</small></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-secondary">{{ $totalLessonPlans }}</h5><small class="text-muted">Lesson Plans</small></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-primary">{{ $totalAssignments }}</h5><small class="text-muted">Assignments</small></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-info">{{ $totalHomework }}</h5><small class="text-muted">Homework</small></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-success">{{ $totalMaterials }}</h5><small class="text-muted">Study Materials</small></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-warning">{{ $totalDiaryEntries }}</h5><small class="text-muted">Diary Entries</small></div></div>
</div>

<div class="row g-2">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-users me-2"></i>Students by Class</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Class','Students']">
                    @foreach($classStudentCounts as $c)
                    <tr><td class="fw-semibold">{{ $c->name }}</td><td>{{ $c->student_count }}</td></tr>
                    @endforeach
                    @if($classStudentCounts->isEmpty())
                    <tr><td colspan="2" class="text-center text-muted py-3">No data</td></tr>
                    @endif
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-file-alt me-2"></i>Lesson Plans by Status</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Status','Count']">
                    @foreach($lessonPlanStatus as $s)
                    <tr><td><span class="badge bg-{{ $s->status=='published'?'success':'warning' }}">{{ ucfirst($s->status) }}</span></td><td>{{ $s->total }}</td></tr>
                    @endforeach
                    @if($lessonPlanStatus->isEmpty())
                    <tr><td colspan="2" class="text-center text-muted py-3">No data</td></tr>
                    @endif
                </x-table>
            </div>
        </div>
    </div>
</div>
@endsection
