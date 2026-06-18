@extends('layouts.app')
@section('title', 'Attendance Detail')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-calendar-check me-2"></i>Attendance Detail</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('attendance.index') }}">Attendance</a></li><li class="breadcrumb-item active">Detail</li></ol></nav>
    </div>
    <a href="{{ route('attendance.edit', 1) }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i>Edit</a>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-list me-2 text-primary"></i>Attendance Records - Grade 10A - Jun 13, 2026</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['#','Student','Roll','Status','Time','Remarks']">
                    @foreach(range(1,10) as $i)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>Student {{ $i }}</td>
                        <td>{{ $i }}</td>
                        <td><span class="badge bg-{{ ['success','danger','warning','info'][$i%4] }}">{{ ['Present','Absent','Late','Leave'][$i%4] }}</span></td>
                        <td>08:{{ sprintf('%02d',rand(0,59)) }} AM</td>
                        <td><small>{{ ['-','Sick','Traffic','Family event','-','-','-','-','-','-'][$i-1] }}</small></td>
                    </tr>
                    @endforeach
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-chart-pie me-2 text-success"></i>Summary</h6></div>
            <div class="card-body">
                <div class="chart-container" style="height:200px"><canvas id="attDetailChart"></canvas></div>
                <hr>
                <div class="d-flex justify-content-between mb-2"><span>Present</span><span class="fw-bold text-success">85%</span></div>
                <div class="d-flex justify-content-between mb-2"><span>Absent</span><span class="fw-bold text-danger">8%</span></div>
                <div class="d-flex justify-content-between mb-2"><span>Late</span><span class="fw-bold text-warning">4%</span></div>
                <div class="d-flex justify-content-between"><span>Leave</span><span class="fw-bold text-info">3%</span></div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
new Chart(document.getElementById('attDetailChart'), {
    type: 'doughnut',
    data: { labels: ['Present','Absent','Late','Leave'], datasets: [{ data: [85,8,4,3], backgroundColor: ['#198754','#dc3545','#ffc107','#0dcaf0'] }] },
    options: { responsive:true, maintainAspectRatio:false, plugins:{legend:{position:'bottom'}} }
});
</script>
@endpush