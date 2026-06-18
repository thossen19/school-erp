@extends('layouts.app')
@section('title', 'Class Timetables')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-clock me-2"></i>Class Timetables</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Timetable</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('timetable.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Create Timetable</a>
        <a href="{{ route('timetable.room-allocation') }}" class="btn btn-outline-info"><i class="fas fa-door-open me-1"></i>Room Allocation</a>
    </div>
</div>

<div class="filter-bar">
    <div class="row g-2 align-items-end">
        <div class="col-md-3"><label class="form-label fw-semibold small">Class</label><select class="form-select form-select-sm"><option>Grade 10</option><option>Grade 9</option></select></div>
        <div class="col-md-3"><label class="form-label fw-semibold small">Section</label><select class="form-select form-select-sm"><option>A</option><option>B</option><option>C</option></select></div>
        <div class="col-md-3"><label class="form-label fw-semibold small">Academic Year</label><select class="form-select form-select-sm"><option>2025-2026</option></select></div>
        <div class="col-md-1"><button class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button></div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-3">
        <h6 class="fw-semibold mb-0"><i class="fas fa-table me-2 text-primary"></i>Grade 10 - Section A</h6>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-chevron-left"></i> Previous</button>
            <button class="btn btn-sm btn-outline-secondary">Next <i class="fas fa-chevron-right"></i></button>
            <button class="btn btn-sm btn-outline-success"><i class="fas fa-print"></i> Print</button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:100px">Time / Day</th>
                        <th>Monday</th>
                        <th>Tuesday</th>
                        <th>Wednesday</th>
                        <th>Thursday</th>
                        <th>Friday</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(['8:00-8:45','8:45-9:30','9:30-10:15','10:15-10:30','10:30-11:15','11:15-12:00','12:00-12:45','12:45-1:30'] as $period)
                    <tr>
                        <td class="fw-semibold small">{{ $period }}</td>
                        @foreach(['Mon','Tue','Wed','Thu','Fri'] as $day)
                        <td class="{{ strpos($period,'10:15-10:30')!==false?'bg-warning bg-opacity-10 text-center':'' }}">
                            @if(strpos($period,'10:15-10:30')!==false)
                                <small class="fw-semibold text-muted"><i class="fas fa-coffee me-1"></i>Break</small>
                            @else
                                <small class="fw-semibold d-block">{{ ['Mathematics','English','Science','History','Physics','Chemistry','Biology','PE','Art','Music','Computer'][array_rand(['Mathematics','English','Science','History','Physics','Chemistry','Biology','PE','Art','Music','Computer'])] }}</small>
                                <small class="text-muted">{{ ['Mr. Johnson','Ms. Davis','Mrs. Smith','Dr. Brown','Mr. Wilson','Ms. Lee','Mrs. Taylor','Mr. Clark'][array_rand(['Mr. Johnson','Ms. Davis','Mrs. Smith','Dr. Brown','Mr. Wilson','Ms. Lee','Mrs. Taylor','Mr. Clark'])] }}</small>
                                <br><small class="badge bg-secondary">Rm {{ rand(101,305) }}</small>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection