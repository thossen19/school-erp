@extends('layouts.app')
@section('title', 'Daily Attendance')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-calendar-check me-2"></i>Daily Attendance</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Attendance</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('attendance.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Mark Attendance</a>
        <a href="{{ route('attendance.report') }}" class="btn btn-outline-info"><i class="fas fa-chart-bar me-1"></i>Reports</a>
    </div>
</div>

<div class="filter-bar">
    <div class="row g-2 align-items-end">
        <div class="col-md-2"><label class="form-label fw-semibold small">Date</label><input type="date" class="form-control form-control-sm" value="{{ date('Y-m-d') }}"></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Class</label><select class="form-select form-select-sm"><option>All</option><option>Grade 1</option><option>Grade 2</option></select></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Section</label><select class="form-select form-select-sm"><option>All</option><option>A</option><option>B</option></select></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Status</label><select class="form-select form-select-sm"><option>All</option><option>Present</option><option>Absent</option><option>Late</option><option>Leave</option></select></div>
        <div class="col-md-1"><button class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button></div>
        <div class="col-md-1"><button class="btn btn-outline-secondary btn-sm w-100"><i class="fas fa-redo-alt"></i></button></div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-3">
        <h6 class="fw-semibold mb-0"><i class="fas fa-calendar-day me-2 text-primary"></i>Attendance for {{ date('F d, Y') }}</h6>
        <div>
            <span class="badge bg-success me-1">Present: 85</span>
            <span class="badge bg-danger me-1">Absent: 8</span>
            <span class="badge bg-warning text-dark me-1">Late: 4</span>
            <span class="badge bg-info">Leave: 3</span>
        </div>
    </div>
    <div class="card-body p-0">
        <x-table :headers="['#','Student ID','Name','Class','Status','Marked By','Time','Remarks']">
            @foreach(range(1,15) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td>STU-{{ sprintf('%04d',$i+50) }}</td>
                <td><a href="#" class="text-decoration-none fw-semibold">{{ ['John Doe','Alice Smith','Bob Johnson','Carol White','David Brown','Eva Green','Frank Black','Grace Lee','Henry Wilson','Ivy Adams','Jack Davis','Kathy Miller','Leo Thomas','Mia Garcia','Noah Wright'][$i-1] }}</a></td>
                <td>Grade {{ rand(1,12) }}-{{ ['A','B','C'][array_rand(['A','B','C'])] }}</td>
                <td>
                    @php $statuses = ['present','absent','late','leave']; $st = $statuses[array_rand($statuses)]; @endphp
                    <span class="status-badge bg-{{ $st=='present'?'success':($st=='absent'?'danger':($st=='late'?'warning text-dark':'info')) }}">{{ ucfirst($st) }}</span>
                </td>
                <td>{{ ['Mr. Johnson','Ms. Davis','Mrs. Smith'][array_rand(['Mr. Johnson','Ms. Davis','Mrs. Smith'])] }}</td>
                <td>08:{{ sprintf('%02d', rand(0,59)) }} AM</td>
                <td><small class="text-muted">{{ ['On time','Sick','Family event','Traffic','-','-','-','-'][array_rand(['On time','Sick','Family event','Traffic','-','-','-','-'])] }}</small></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>

<x-pagination :paginator="$records" />
@endsection