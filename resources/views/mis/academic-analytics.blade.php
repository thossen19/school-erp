@extends('layouts.app')
@section('title', 'Academic Analytics')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-book-open me-2"></i>Academic Analytics</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">MIS Reports</li><li class="breadcrumb-item active">Academic Analytics</li></ol></nav>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-users me-2 text-primary"></i>Students per Class</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Class','Students']">
                    @forelse($classWiseStudents as $c)
                    <tr>
                        <td class="fw-semibold">{{ $c->class_name }}</td>
                        <td><span class="badge bg-primary">{{ $c->total }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-center py-3 text-muted">No data</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-chart-line me-2 text-success"></i>Exam Performance</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Exam','Avg %','Passed','Total']">
                    @forelse($examPerformance as $e)
                    <tr>
                        <td class="fw-semibold">{{ $e->exam_name }}</td>
                        <td><span class="badge bg-{{ $e->avg_percentage >= 75 ? 'success' : ($e->avg_percentage >= 40 ? 'warning' : 'danger') }}">{{ $e->avg_percentage }}%</span></td>
                        <td>{{ $e->passed }}</td>
                        <td>{{ $e->total_students }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-3 text-muted">No exam data</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
</div>
<div class="row g-3 mt-3">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-layer-group me-2 text-info"></i>Section-wise Distribution</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Class','Section','Students']">
                    @forelse($sectionWise as $s)
                    <tr>
                        <td class="fw-semibold">{{ $s->class_name }}</td>
                        <td>{{ $s->section_name }}</td>
                        <td><span class="badge bg-info">{{ $s->total }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center py-3 text-muted">No section data</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
</div>
@endsection