@extends('layouts.app')
@section('title', 'Lesson Plan Detail')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-file-alt me-2"></i>Lesson Plan Detail</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ url()->previous() }}">Academic</a></li><li class="breadcrumb-item active">Lesson Plan Details</li></ol></nav>
    </div>
    <div class="d-flex gap-2"><a href="{{ route_if_exists('lesson-plans.edit',1) }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i>Edit</a><button class="btn btn-outline-success"><i class="fas fa-print me-1"></i>Print</button></div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-8">
                <h5 class="fw-bold">Introduction to Algebra</h5>
                <p class="text-muted">Grade 10 - Mathematics | Duration: 45 min | Jun 15, 2026</p>
                <hr>
                <h6 class="fw-semibold">Learning Objectives</h6>
                <p>Students will understand basic algebraic concepts including variables, expressions, and equations.</p>
                <h6 class="fw-semibold mt-3">Materials Needed</h6>
                <p>Textbook Chapter 5, Whiteboard, Markers, Worksheets</p>
                <h6 class="fw-semibold mt-3">Procedure</h6>
                <ol>
                    <li>Warm-up (5 min): Review previous concepts</li>
                    <li>Introduction (10 min): Explain variables and expressions</li>
                    <li>Guided Practice (15 min): Solve sample problems together</li>
                    <li>Independent Practice (10 min): Worksheet exercises</li>
                    <li>Closure (5 min): Review and Q&A</li>
                </ol>
                <h6 class="fw-semibold mt-3">Assessment</h6>
                <p>Worksheet completion and exit ticket questions</p>
            </div>
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="fw-semibold">Details</h6>
                        <div class="mb-2"><small class="text-muted d-block">Status</small><span class="badge bg-success">Published</span></div>
                        <div class="mb-2"><small class="text-muted d-block">Created By</small><span>Mr. Johnson</span></div>
                        <div class="mb-2"><small class="text-muted d-block">Created Date</small><span>Jun 10, 2026</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection