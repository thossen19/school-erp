@extends('layouts.app')
@section('title', 'Executive Dashboard')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-tachometer-alt me-2"></i>Executive Dashboard</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">MIS Reports</li><li class="breadcrumb-item active">Executive Dashboard</li></ol></nav>
    </div>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <x-stats-card icon="fa-user-graduate" title="Total Students" :value="$totalStudents" color="primary" />
    </div>
    <div class="col-md-2">
        <x-stats-card icon="fa-users" title="Employees" :value="$totalEmployees" color="info" />
    </div>
    <div class="col-md-2">
        <x-stats-card icon="fa-school" title="Classes" :value="$totalClasses" color="success" />
    </div>
    <div class="col-md-2">
        <x-stats-card icon="fa-check-circle" title="Present Today" :value="$todayPresent" color="success" />
    </div>
    <div class="col-md-2">
        <x-stats-card icon="fa-clock" title="Absent Today" :value="$todayAbsent" color="danger" />
    </div>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <x-stats-card icon="fa-hourglass-half" title="Pending Fees" :value="$pendingFees" color="warning" />
    </div>
    <div class="col-md-2">
        <x-stats-card icon="fa-user-plus" title="New Admissions (30d)" :value="$recentAdmissions" color="info" />
    </div>
    <div class="col-md-2">
        <x-stats-card icon="fa-money-bill-wave" title="Monthly Fees" :value="number_format($monthlyFeeCollected, 0)" color="success" />
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-venus-mars me-2 text-primary"></i>Gender Breakdown</h6></div>
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
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-user-plus me-2 text-info"></i>Recent Registrations</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['#','Name','Admission No']">
                    @forelse($recentStudents as $i => $s)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td class="fw-semibold">{{ $s->first_name }} {{ $s->last_name }}</td>
                        <td>{{ $s->admission_no }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center py-3 text-muted">No recent registrations</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
</div>
@endsection