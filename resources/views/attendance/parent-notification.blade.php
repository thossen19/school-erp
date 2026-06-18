@extends('layouts.app')
@section('title', 'Parent Notification')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-bell me-2"></i>Parent Notification</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Attendance</a></li><li class="breadcrumb-item active">Parent Notification</li></ol></nav>
    </div>
</div>
<div class="row g-2 mb-3">
    <div class="col-md-4"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-danger">{{ $absentCount }}</h5><small class="text-muted">Absent Today</small></div></div>
    <div class="col-md-4"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-warning">{{ $lateCount }}</h5><small class="text-muted">Late Today</small></div></div>
    <div class="col-md-4"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-primary">{{ $absentCount + $lateCount }}</h5><small class="text-muted">Total Notifications</small></div></div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0">Students Requiring Parent Notification (Today)</h6></div>
    <div class="card-body p-0">
        <x-table :headers="['#','Student','Code','Class','Date','Status','Remarks']">
            @foreach($lateRecords as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td>{{ $r->first_name }} {{ $r->last_name }}</td>
                <td>{{ $r->admission_no ?? '-' }}</td>
                <td>{{ $r->class_name ?? '-' }}</td>
                <td>{{ $r->date }}</td>
                <td><span class="badge bg-{{ $r->status=='absent'?'danger':'warning text-dark' }}">{{ ucfirst($r->status) }}</span></td>
                <td>{{ $r->remarks ?? '-' }}</td>
            </tr>
            @endforeach
            @if($lateRecords->isEmpty())<tr><td colspan="7" class="text-center text-muted py-3">All students present today</td></tr>@endif
        </x-table>
    </div>
</div>
@endsection
