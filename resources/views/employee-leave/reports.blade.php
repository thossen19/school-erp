@extends('layouts.app')
@section('title', 'Leave Reports')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-chart-pie me-2"></i>Leave Reports</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">HR</li><li class="breadcrumb-item">Employee Leave Management</li><li class="breadcrumb-item active">Reports</li></ol></nav>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <i class="fas fa-inbox fs-4 text-primary"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $totalRequests }}</h3>
                        <small class="text-muted">Total Requests</small>
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
                        <i class="fas fa-clock fs-4 text-warning"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $pendingRequests }}</h3>
                        <small class="text-muted">Pending</small>
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
                        <i class="fas fa-check-circle fs-4 text-success"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $approvedThisMonth }}</h3>
                        <small class="text-muted">Approved This Month</small>
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
                        <i class="fas fa-times-circle fs-4 text-danger"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $rejectedThisMonth }}</h3>
                        <small class="text-muted">Rejected This Month</small>
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
                <h6 class="fw-semibold mb-0"><i class="fas fa-circle me-2"></i>By Status</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Status</th><th class="text-end">Count</th></tr>
                        </thead>
                        <tbody>
                            @forelse($byStatus as $s)
                            <tr>
                                <td>{{ ucfirst($s->status) }}</td>
                                <td class="text-end"><span class="badge bg-primary bg-opacity-10 text-primary fs-6">{{ $s->total }}</span></td>
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
                <h6 class="fw-semibold mb-0"><i class="fas fa-tags me-2"></i>By Leave Type</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Leave Type</th><th class="text-end">Count</th></tr>
                        </thead>
                        <tbody>
                            @forelse($byType as $t)
                            <tr>
                                <td>{{ $t->leaveType->name ?? 'N/A' }}</td>
                                <td class="text-end"><span class="badge bg-success bg-opacity-10 text-success fs-6">{{ $t->total }}</span></td>
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
                <h6 class="fw-semibold mb-0"><i class="fas fa-calendar-week me-2"></i>By Month ({{ date('Y') }})</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Month</th><th class="text-end">Count</th></tr>
                        </thead>
                        <tbody>
                            @forelse($byMonth as $m)
                            <tr>
                                <td>{{ DateTime::createFromFormat('!m', $m->month)->format('F') }}</td>
                                <td class="text-end"><span class="badge bg-info bg-opacity-10 text-info fs-6">{{ $m->total }}</span></td>
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
                <h6 class="fw-semibold mb-0"><i class="fas fa-star me-2"></i>Key Metrics</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded">
                            <div class="fs-4 fw-bold">{{ $totalLeaveTypes }}</div>
                            <small class="text-muted">Leave Types</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded">
                            <div class="fs-4 fw-bold">{{ $activePolicies }}</div>
                            <small class="text-muted">Active Policies</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded">
                            <div class="fs-4 fw-bold {{ $lowBalanceEmployees > 0 ? 'text-danger' : '' }}">{{ $lowBalanceEmployees }}</div>
                            <small class="text-muted">Low Balance Employees</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded">
                            <div class="fs-4 fw-bold">{{ $pendingEncashments }}</div>
                            <small class="text-muted">Pending Encashments</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-header bg-white py-3">
                <h6 class="fw-semibold mb-0"><i class="fas fa-calendar-check me-2"></i>Upcoming Holidays</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Date</th><th>Holiday</th><th>Type</th></tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingHolidays as $h)
                            <tr>
                                <td>{{ $h->date->format('M d, Y') }}</td>
                                <td class="fw-semibold">{{ $h->name }}</td>
                                <td><span class="badge bg-info bg-opacity-10 text-info">{{ ucfirst($h->type) }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-3 text-muted">No upcoming holidays</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
