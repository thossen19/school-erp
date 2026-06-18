@extends('layouts.app')
@section('title', 'Academic')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-book-open me-2"></i>Academic Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Academic</li></ol></nav>
    </div>
</div>

<div class="row g-2 mb-3">
    <div class="col-md-2"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-primary">{{ $stats['classes'] }}</h5><small class="text-muted">Classes</small></div></div>
    <div class="col-md-2"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-info">{{ $stats['sections'] }}</h5><small class="text-muted">Sections</small></div></div>
    <div class="col-md-2"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-success">{{ $stats['subjects'] }}</h5><small class="text-muted">Subjects</small></div></div>
    <div class="col-md-2"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-warning">{{ $stats['lessonPlans'] }}</h5><small class="text-muted">Lesson Plans</small></div></div>
    <div class="col-md-2"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-secondary">{{ $stats['assignments'] }}</h5><small class="text-muted">Assignments</small></div></div>
    <div class="col-md-2"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-danger">{{ $stats['homework'] }}</h5><small class="text-muted">Homework</small></div></div>
</div>

<div class="row g-2 mb-3">
    <div class="col-md-3"><a href="{{ route('academic.academic-years') }}" class="btn btn-outline-primary w-100"><i class="fas fa-calendar me-1"></i>Academic Years</a></div>
    <div class="col-md-3"><a href="{{ route('academic.classes') }}" class="btn btn-outline-primary w-100"><i class="fas fa-chalkboard me-1"></i>Classes</a></div>
    <div class="col-md-3"><a href="{{ route('academic.sections') }}" class="btn btn-outline-primary w-100"><i class="fas fa-layer-group me-1"></i>Sections</a></div>
    <div class="col-md-3"><a href="{{ route('academic.subjects') }}" class="btn btn-outline-primary w-100"><i class="fas fa-book me-1"></i>Subjects</a></div>
    <div class="col-md-3"><a href="{{ route('academic.curriculum') }}" class="btn btn-outline-primary w-100"><i class="fas fa-sitemap me-1"></i>Curriculum</a></div>
    <div class="col-md-3"><a href="{{ route('academic.lesson-plans') }}" class="btn btn-outline-primary w-100"><i class="fas fa-file-alt me-1"></i>Lesson Plans</a></div>
    <div class="col-md-3"><a href="{{ route('academic.assignments') }}" class="btn btn-outline-primary w-100"><i class="fas fa-tasks me-1"></i>Assignments</a></div>
    <div class="col-md-3"><a href="{{ route('academic.homework') }}" class="btn btn-outline-primary w-100"><i class="fas fa-home me-1"></i>Homework</a></div>
    <div class="col-md-3"><a href="{{ route('academic.study-materials') }}" class="btn btn-outline-primary w-100"><i class="fas fa-folder me-1"></i>Study Materials</a></div>
    <div class="col-md-3"><a href="{{ route('academic.teacher-diary') }}" class="btn btn-outline-primary w-100"><i class="fas fa-book me-1"></i>Teacher Diary</a></div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-file-alt me-2 text-primary"></i>Recent Lesson Plans</h6></div>
    <div class="card-body p-0">
        <x-table :headers="['#','Title','Subject','Status','Created']">
            @foreach($recentLessonPlans as $i=>$lp)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="fw-semibold">{{ $lp->title ?? 'Untitled' }}</td>
                <td>{{ $lp->subject_name ?? '-' }}</td>
                <td><span class="badge bg-{{ $lp->status=='published'?'success':'warning' }}">{{ ucfirst($lp->status ?? 'draft') }}</span></td>
                <td>{{ \Carbon\Carbon::parse($lp->created_at)->format('M d, Y') }}</td>
            </tr>
            @endforeach
            @if($recentLessonPlans->isEmpty())
            <tr><td colspan="5" class="text-center text-muted py-4">No lesson plans yet</td></tr>
            @endif
        </x-table>
    </div>
</div>
@endsection
