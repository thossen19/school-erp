@extends('layouts.app')
@section('title', 'Student Analytics')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-user-graduate me-2"></i>Student Analytics</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">MIS Reports</li><li class="breadcrumb-item active">Student Analytics</li></ol></nav>
    </div>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <x-stats-card icon="fa-users" title="Total Students" :value="$total" color="primary" />
    </div>
</div>
<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-venus-mars me-2 text-primary"></i>Gender Distribution</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Gender','Count']">
                    @forelse($genderBreakdown as $g)
                    <tr>
                        <td>{{ ucfirst($g->gender ?? 'Unknown') }}</td>
                        <td><span class="badge bg-primary">{{ $g->total }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-center py-3 text-muted">No data</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-layer-group me-2 text-success"></i>Class Distribution</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Class','Count']">
                    @forelse($classDistribution as $c)
                    <tr>
                        <td class="fw-semibold">{{ $c->name }}</td>
                        <td><span class="badge bg-success">{{ $c->total }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-center py-3 text-muted">No data</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-flag me-2 text-warning"></i>Status Breakdown</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Status','Count']">
                    @forelse($statusBreakdown as $s)
                    <tr>
                        <td>{{ ucfirst($s->status ?? 'Unknown') }}</td>
                        <td><span class="badge bg-{{ $s->status === 'active' ? 'success' : 'secondary' }}">{{ $s->total }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-center py-3 text-muted">No data</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
</div>
<div class="row g-3 mt-3">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-chart-line me-2 text-info"></i>Admission Trend (Last 12 months)</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Year','Month','Admissions']">
                    @forelse($admissionTrend as $a)
                    <tr>
                        <td>{{ $a->year }}</td>
                        <td>{{ date('F', mktime(0, 0, 0, $a->month, 1)) }}</td>
                        <td><span class="badge bg-info">{{ $a->total }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center py-3 text-muted">No admission data</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
</div>
@endsection