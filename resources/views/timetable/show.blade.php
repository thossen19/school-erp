@extends('layouts.app')
@section('title', 'Timetable Detail')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-clock me-2"></i>Timetable Detail</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('timetable.index') }}">Timetable</a></li><li class="breadcrumb-item active">Grade 10A</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('timetable.edit',1) }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i>Edit</a>
        <button class="btn btn-outline-success"><i class="fas fa-print me-1"></i>Print</button>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-info me-2"></i>Grade 10 - Section A Timetable</h6></div>
    <div class="card-body">
        <p><strong>Academic Year:</strong> 2025-2026 | <strong>Periods:</strong> 8/day | <strong>Duration:</strong> 45 min | <strong>Break:</strong> 15 min</p>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr><th>Time</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th></tr>
                </thead>
                <tbody>
                    @foreach(range(1,8) as $p)
                    <tr>
                        <td class="fw-semibold small">{{ sprintf('%02d:00',7+$p) }}-{{ sprintf('%02d:45',7+$p) }}</td>
                        @foreach(range(1,5) as $d)
                        <td>
                            <small class="fw-semibold">{{ ['Mathematics','English','Science','History','Art','Physics','Chemistry','PE'][$p%8] }}</small>
                            <br><small class="text-muted">Rm {{ rand(200,305) }}</small>
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