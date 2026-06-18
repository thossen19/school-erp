@extends('layouts.app')
@section('title', 'Timetable Reports')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chart-bar me-2"></i>Timetable Reports</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Timetable</a></li><li class="breadcrumb-item active">Timetable Reports</li></ol></nav>
    </div>
</div>
<div class="row g-2 mb-3">
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-primary">{{ $totalTimetables }}</h5><small class="text-muted">Total Timetables</small></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-success">{{ $activeTimetables }}</h5><small class="text-muted">Active</small></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-info">{{ $totalPeriods }}</h5><small class="text-muted">Total Periods</small></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-warning">{{ $totalRooms }}</h5><small class="text-muted">Rooms</small></div></div>
</div>
<div class="row g-2">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-calendar-day me-2"></i>Periods by Day</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Day','Periods']">
                    @foreach($byDay as $d)
                    <tr><td>{{ $d->day_of_week }}</td><td class="fw-bold">{{ $d->total }}</td></tr>
                    @endforeach
                    @if($byDay->isEmpty())<tr><td colspan="2" class="text-center text-muted py-3">No data</td></tr>@endif
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-chart-bar me-2"></i>Timetables by Class</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Class','Timetables']">
                    @foreach($byClass as $c)
                    <tr><td>{{ $c->class_name ?? 'N/A' }}</td><td class="fw-bold">{{ $c->total }}</td></tr>
                    @endforeach
                    @if($byClass->isEmpty())<tr><td colspan="2" class="text-center text-muted py-3">No data</td></tr>@endif
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-clock me-2"></i>Periods by Class</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Class','Periods']">
                    @foreach($periodCounts as $p)
                    <tr><td>{{ $p->class_name ?? 'N/A' }}</td><td class="fw-bold">{{ $p->total }}</td></tr>
                    @endforeach
                    @if($periodCounts->isEmpty())<tr><td colspan="2" class="text-center text-muted py-3">No data</td></tr>@endif
                </x-table>
            </div>
        </div>
    </div>
</div>
@endsection
