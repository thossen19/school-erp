@extends('layouts.app')
@section('title', 'Student Timeline')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-history me-2"></i>Student Timeline</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('student.index') }}">Students</a></li><li class="breadcrumb-item"><a href="{{ route('student.show',1) }}">John Doe</a></li><li class="breadcrumb-item active">Timeline</li></ol></nav>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <div class="avatar-circle bg-primary mx-auto mb-2" style="width:64px;height:64px;font-size:1.5rem">JD</div>
                <h6 class="fw-semibold">John Doe</h6>
                <p class="text-muted small">STU-0001 | Grade 10A</p>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom py-3">
                <h6 class="fw-semibold mb-0"><i class="fas fa-history me-2 text-primary"></i>Activity Timeline</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <small class="timeline-date">Jun 12, 2026 - 9:00 AM</small>
                        <small class="fw-semibold d-block">Fee Payment</small>
                        <small class="text-muted">Tuition fee for June paid - $2,500</small>
                    </div>
                    <div class="timeline-item">
                        <small class="timeline-date">Jun 10, 2026 - 2:30 PM</small>
                        <small class="fw-semibold d-block">Attendance Marked</small>
                        <small class="text-muted">Present - Full day</small>
                    </div>
                    <div class="timeline-item">
                        <small class="timeline-date">Jun 8, 2026 - 11:15 AM</small>
                        <small class="fw-semibold d-block">Award Received</small>
                        <small class="text-muted">Best Student of the Month - May 2026</small>
                    </div>
                    <div class="timeline-item">
                        <small class="timeline-date">Jun 5, 2026 - 10:00 AM</small>
                        <small class="fw-semibold d-block">Exam Result Published</small>
                        <small class="text-muted">Midterm - Grade A (92%)</small>
                    </div>
                    <div class="timeline-item">
                        <small class="timeline-date">Jun 1, 2026 - 8:00 AM</small>
                        <small class="fw-semibold d-block">Homework Assigned</small>
                        <small class="text-muted">Math: Chapter 5 exercises</small>
                    </div>
                    <div class="timeline-item">
                        <small class="timeline-date">May 28, 2026 - 3:00 PM</small>
                        <small class="fw-semibold d-block">Discipline Record</small>
                        <small class="text-muted">Verbal warning for late arrival</small>
                    </div>
                    <div class="timeline-item">
                        <small class="timeline-date">Mar 15, 2026 - 9:00 AM</small>
                        <small class="fw-semibold d-block">Student Admitted</small>
                        <small class="text-muted">Admitted to Grade 10A</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection