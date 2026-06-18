@extends('layouts.app')
@section('title', 'Alumni Reports')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chart-bar me-2"></i>Alumni Reports</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Alumni</li><li class="breadcrumb-item active">Reports</li></ol></nav>
    </div>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <x-stats-card icon="fa-user-graduate" title="Total Alumni" :value="$totalAlumni" color="primary" />
    </div>
    <div class="col-md-2">
        <x-stats-card icon="fa-check-circle" title="Verified" :value="$verifiedAlumni" color="success" />
    </div>
    <div class="col-md-2">
        <x-stats-card icon="fa-hand-holding-usd" title="Total Donations" :value="number_format($totalDonations, 2)" color="warning" />
    </div>
    <div class="col-md-2">
        <x-stats-card icon="fa-briefcase" title="Active Jobs" :value="$activeJobs" color="danger" />
    </div>
    <div class="col-md-2">
        <x-stats-card icon="fa-calendar" title="Upcoming Events" :value="$upcomingEvents" color="info" />
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-chart-line me-2 text-primary"></i>Year-wise Alumni Breakdown</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Graduation Year','Total']">
                    @forelse($yearlyBreakdown as $yb)
                    <tr>
                        <td class="fw-semibold">{{ $yb->graduation_year }}</td>
                        <td><span class="badge bg-primary">{{ $yb->total }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-center py-3 text-muted">No data available.</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-hand-holding-usd me-2 text-warning"></i>Donation Stats by Mode</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Mode','Count','Total Amount']">
                    @forelse($donationStats as $ds)
                    <tr>
                        <td>{{ $ds->payment_mode ?? 'N/A' }}</td>
                        <td><span class="badge bg-info">{{ $ds->total }}</span></td>
                        <td class="fw-bold">{{ number_format($ds->total_amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center py-3 text-muted">No donation data.</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
</div>
<div class="row g-3 mt-3">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-users me-2 text-success"></i>Recent Alumni Registrations</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Name','Graduation Year','Occupation','Company']">
                    @forelse($recentAlumni as $ra)
                    <tr>
                        <td class="fw-semibold">{{ $ra->first_name }} {{ $ra->last_name }}</td>
                        <td><span class="badge bg-secondary">{{ $ra->graduation_year }}</span></td>
                        <td>{{ $ra->current_occupation ?? '-' }}</td>
                        <td>{{ $ra->company ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-3 text-muted">No recent registrations.</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
</div>
@endsection