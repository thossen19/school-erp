@extends('layouts.app')
@section('title', 'AI Timetable Generator')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-robot me-2"></i>AI Timetable Generator</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Timetable</a></li><li class="breadcrumb-item active">AI Timetable Generator</li></ol></nav>
    </div>
</div>
<div class="row g-2 mb-3">
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-primary">{{ $totalTimetables }}</h5><small class="text-muted">Existing Timetables</small></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-info">{{ $totalPeriods }}</h5><small class="text-muted">Total Periods</small></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-success">{{ $classes->count() }}</h5><small class="text-muted">Classes</small></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-warning">{{ $subjects->count() }}</h5><small class="text-muted">Subjects</small></div></div>
</div>
<div class="row g-2">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-database me-2"></i>Available Resources</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Resource','Count']">
                    <tr><td>Classes</td><td>{{ $classes->count() }}</td></tr>
                    <tr><td>Subjects</td><td>{{ $subjects->count() }}</td></tr>
                    <tr><td>Teachers</td><td>{{ $teachers->count() }}</td></tr>
                    <tr><td>Rooms</td><td>{{ $rooms->count() }}</td></tr>
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-cog me-2"></i>Generator Settings</h6></div>
            <div class="card-body">
                <div class="mb-2"><label class="form-label fw-semibold">Working Days</label><div class="d-flex gap-2 flex-wrap">@foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $d)<div class="form-check"><input class="form-check-input" type="checkbox" checked><label class="form-check-label">{{ $d }}</label></div>@endforeach</div></div>
                <div class="row g-2">
                    <div class="col-6"><label class="form-label fw-semibold">Periods per Day</label><input type="number" class="form-control form-control-sm" value="8"></div>
                    <div class="col-6"><label class="form-label fw-semibold">Period Duration (min)</label><input type="number" class="form-control form-control-sm" value="45"></div>
                </div>
                <button class="btn btn-primary btn-sm mt-2"><i class="fas fa-magic me-1"></i>Generate Timetable</button>
            </div>
        </div>
    </div>
</div>
<div class="card shadow-sm border-0 mt-2">
    <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-list me-2"></i>Classes</h6></div>
    <div class="card-body p-0">
        <x-table :headers="['Class','Subjects Available','Teachers Available']">
            @foreach($classes as $c)
            <tr><td>{{ $c->name }}</td><td>{{ $subjects->count() }}</td><td>{{ $teachers->count() }}</td></tr>
            @endforeach
        </x-table>
    </div>
</div>
@endsection
