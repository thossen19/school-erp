@extends('layouts.app')
@section('title', 'Face Recognition Ready')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-camera me-2"></i>Face Recognition Ready</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Attendance</a></li><li class="breadcrumb-item active">Face Recognition Ready</li></ol></nav>
    </div>
</div>
<div class="row g-2 mb-3">
    <div class="col-md-4"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-{{ $faceEnabled?'success':'secondary' }}">{{ $faceEnabled?'Enabled':'Disabled' }}</h5><small class="text-muted">Face Recognition System</small></div></div>
    <div class="col-md-4"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-primary">{{ $settings->grace_period_minutes ?? 0 }} min</h5><small class="text-muted">Grace Period</small></div></div>
    <div class="col-md-4"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-primary">{{ $settings->late_threshold_minutes ?? 15 }} min</h5><small class="text-muted">Late Threshold</small></div></div>
</div>
@if($settings)
<div class="card shadow-sm border-0 mb-3">
    <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0">Face Recognition Settings</h6></div>
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">Attendance Type</dt><dd class="col-sm-9">{{ ucfirst($settings->attendance_type) }}</dd>
            <dt class="col-sm-3">Biometric Enabled</dt><dd class="col-sm-9">{{ $settings->biometric_enabled ? 'Yes' : 'No' }}</dd>
            <dt class="col-sm-3">RFID Enabled</dt><dd class="col-sm-9">{{ $settings->rfid_enabled ? 'Yes' : 'No' }}</dd>
            <dt class="col-sm-3">UHF Enabled</dt><dd class="col-sm-9">{{ $settings->uhf_enabled ? 'Yes' : 'No' }}</dd>
            <dt class="col-sm-3">Face Recognition</dt><dd class="col-sm-9"><span class="badge bg-{{ $settings->face_recognition_enabled?'success':'secondary' }}">{{ $settings->face_recognition_enabled ? 'Ready' : 'Not Configured' }}</span></dd>
            <dt class="col-sm-3">Days per Week</dt><dd class="col-sm-9">{{ $settings->days_per_week }}</dd>
            <dt class="col-sm-3">Late Threshold</dt><dd class="col-sm-9">{{ $settings->late_threshold_minutes }} minutes</dd>
            <dt class="col-sm-3">Half Day After</dt><dd class="col-sm-9">{{ $settings->half_day_threshold_minutes }} minutes</dd>
        </dl>
    </div>
</div>
@endif
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0">Recent Biometric Attendance Records</h6></div>
    <div class="card-body p-0">
        <x-table :headers="['#','Student','Class','Date','Time In','Time Out','Status']">
            @foreach($records as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td>{{ $r->first_name }} {{ $r->last_name }}</td>
                <td>{{ $r->class_name ?? '-' }}</td>
                <td>{{ $r->date }}</td>
                <td>{{ $r->time_in ? \Carbon\Carbon::parse($r->time_in)->format('H:i') : '-' }}</td>
                <td>{{ $r->time_out ? \Carbon\Carbon::parse($r->time_out)->format('H:i') : '-' }}</td>
                <td><span class="badge bg-{{ $r->status=='present'?'success':($r->status=='absent'?'danger':($r->status=='late'?'warning text-dark':'info')) }}">{{ ucfirst($r->status) }}</span></td>
            </tr>
            @endforeach
            @if($records->isEmpty())<tr><td colspan="7" class="text-center text-muted py-3">No biometric records</td></tr>@endif
        </x-table>
    </div>
    <x-pagination :paginator="$records" />
</div>
@endsection
