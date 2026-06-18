@extends('layouts.app')
@section('title', 'Employee Reports')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-chart-bar me-2"></i>Employee Reports</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">HR</li><li class="breadcrumb-item active">Employee Reports</li></ol></nav>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <i class="fas fa-users fs-4 text-primary"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $totalEmployees }}</h3>
                        <small class="text-muted">Total Employees</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <i class="fas fa-user-check fs-4 text-success"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $activeEmployees }}</h3>
                        <small class="text-muted">Active Employees</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                        <i class="fas fa-user-plus fs-4 text-warning"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $newHiresYear }}</h3>
                        <small class="text-muted">New Hires ({{ date('Y') }})</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3">
                        <i class="fas fa-calendar-exclamation fs-4 text-danger"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $upcomingEvaluations }}</h3>
                        <small class="text-muted">Upcoming Evaluations</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="fw-semibold mb-0"><i class="fas fa-building me-2"></i>Employees by Department</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Department</th><th class="text-end">Count</th></tr>
                        </thead>
                        <tbody>
                            @forelse($byDepartment as $d)
                            <tr>
                                <td>{{ $d->department->name ?? 'N/A' }}</td>
                                <td class="text-end"><span class="badge bg-primary bg-opacity-10 text-primary fs-6">{{ $d->total }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="2" class="text-center py-3 text-muted">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="fw-semibold mb-0"><i class="fas fa-briefcase me-2"></i>Employees by Designation</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Designation</th><th class="text-end">Count</th></tr>
                        </thead>
                        <tbody>
                            @forelse($byDesignation as $d)
                            <tr>
                                <td>{{ $d->designation->name ?? 'N/A' }}</td>
                                <td class="text-end"><span class="badge bg-success bg-opacity-10 text-success fs-6">{{ $d->total }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="2" class="text-center py-3 text-muted">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="fw-semibold mb-0"><i class="fas fa-clock me-2"></i>Employees by Employment Type</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Type</th><th class="text-end">Count</th></tr>
                        </thead>
                        <tbody>
                            @forelse($byEmploymentType as $d)
                            <tr>
                                <td>{{ ucfirst($d->employment_type) }}</td>
                                <td class="text-end"><span class="badge bg-info bg-opacity-10 text-info fs-6">{{ $d->total }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="2" class="text-center py-3 text-muted">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="fw-semibold mb-0"><i class="fas fa-venus-mars me-2"></i>Employees by Gender</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Gender</th><th class="text-end">Count</th></tr>
                        </thead>
                        <tbody>
                            @forelse($byGender as $d)
                            <tr>
                                <td>{{ ucfirst($d->gender) }}</td>
                                <td class="text-end"><span class="badge bg-warning bg-opacity-10 text-warning fs-6">{{ $d->total }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="2" class="text-center py-3 text-muted">No data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-2">
    <div class="col-xl-6">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-purple bg-opacity-10 p-3 me-3" style="background:rgba(111,66,193,0.1)!important;">
                        <i class="fas fa-file-alt fs-4" style="color:#6f42c1;"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $expiringDocuments }}</h3>
                        <small class="text-muted">Documents Expiring in 30 Days</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-teal bg-opacity-10 p-3 me-3" style="background:rgba(32,201,151,0.1)!important;">
                        <i class="fas fa-user-graduate fs-4" style="color:#20c997;"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $newHiresMonth }}</h3>
                        <small class="text-muted">New Hires This Month</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
