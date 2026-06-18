@extends('layouts.app')
@section('title', 'Attendance Analytics')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chart-bar me-2"></i>Attendance Analytics</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Attendance</a></li><li class="breadcrumb-item active">Attendance Analytics</li></ol></nav>
    </div>
</div>
<div class="row g-2 mb-3">
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-primary">{{ $totalRecords }}</h5><small class="text-muted">Total Records</small></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-info">{{ $todayCount }}</h5><small class="text-muted">Today's Entries</small></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-warning">{{ $lateThreshold }} min</h5><small class="text-muted">Late Threshold</small></div></div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center p-3">
            @php $totalPercent = $totalRecords > 0 ? round($statusSummary->where('status','present')->sum('total') / $totalRecords * 100) : 0; @endphp
            <h5 class="fw-bold text-success">{{ $totalPercent }}%</h5><small class="text-muted">Avg Attendance Rate</small>
        </div>
    </div>
</div>
<div class="row g-2">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-pie-chart me-2"></i>Status Summary</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Status','Count']">
                    @php $colors=['present'=>'success','absent'=>'danger','late'=>'warning text-dark','half_day'=>'info']; @endphp
                    @foreach($statusSummary as $s)
                    <tr><td><span class="badge bg-{{ $colors[$s->status] ?? 'secondary' }}">{{ ucfirst($s->status) }}</span></td><td>{{ $s->total }}</td></tr>
                    @endforeach
                    @if($statusSummary->isEmpty())<tr><td colspan="2" class="text-center text-muted py-3">No data</td></tr>@endif
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-chart-bar me-2"></i>Attendance by Class</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Class','Present','Absent','Late','Half Day']">
                    @php
                        $classData = [];
                        foreach($classSummary as $c) {
                            $classData[$c->class_name ?? 'Unknown'][$c->status] = $c->total;
                        }
                    @endphp
                    @foreach($classData as $className => $statuses)
                    <tr>
                        <td class="fw-semibold">{{ $className }}</td>
                        <td><span class="text-success fw-bold">{{ $statuses['present'] ?? 0 }}</span></td>
                        <td><span class="text-danger fw-bold">{{ $statuses['absent'] ?? 0 }}</span></td>
                        <td><span class="text-warning fw-bold">{{ $statuses['late'] ?? 0 }}</span></td>
                        <td>{{ $statuses['half_day'] ?? 0 }}</td>
                    </tr>
                    @endforeach
                    @if(empty($classData))<tr><td colspan="5" class="text-center text-muted py-3">No data</td></tr>@endif
                </x-table>
            </div>
        </div>
    </div>
</div>
<div class="card shadow-sm border-0 mt-2">
    <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-calendar-day me-2"></i>Daily Trends (Last 30 Days)</h6></div>
    <div class="card-body p-0">
        <x-table :headers="['Date','Present','Absent','Late','Half Day']">
            @php $trendData = []; foreach($dailyCounts as $d) { $trendData[$d->date][$d->status] = $d->total; } @endphp
            @foreach($trendData as $date => $statuses)
            <tr>
                <td>{{ $date }}</td>
                <td><span class="text-success fw-bold">{{ $statuses['present'] ?? 0 }}</span></td>
                <td><span class="text-danger fw-bold">{{ $statuses['absent'] ?? 0 }}</span></td>
                <td><span class="text-warning fw-bold">{{ $statuses['late'] ?? 0 }}</span></td>
                <td>{{ $statuses['half_day'] ?? 0 }}</td>
            </tr>
            @endforeach
            @if(empty($trendData))<tr><td colspan="5" class="text-center text-muted py-3">No data</td></tr>@endif
        </x-table>
    </div>
</div>
@endsection
