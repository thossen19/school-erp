@extends('layouts.app')
@section('title', 'Alumni Portal')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-tachometer-alt me-2"></i>Alumni Portal</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Alumni</li><li class="breadcrumb-item active">Portal</li></ol></nav>
    </div>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <x-stats-card icon="fa-user-graduate" title="Total Alumni" :value="$total" color="primary" />
    </div>
    <div class="col-md-2">
        <x-stats-card icon="fa-check-circle" title="Verified" :value="$verified" color="success" />
    </div>
    <div class="col-md-2">
        <x-stats-card icon="fa-calendar" title="Upcoming Events" :value="$upcomingEvents->count()" color="info" />
    </div>
    <div class="col-md-2">
        <x-stats-card icon="fa-hand-holding-usd" title="Total Donations" :value="number_format($totalDonations, 2)" color="warning" />
    </div>
    <div class="col-md-2">
        <x-stats-card icon="fa-briefcase" title="Active Jobs" :value="$activeJobs" color="danger" />
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-calendar-alt me-2 text-info"></i>Upcoming Events</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Title','Date','Venue']">
                    @forelse($upcomingEvents as $e)
                    <tr>
                        <td class="fw-semibold">{{ $e->title }}</td>
                        <td>{{ $e->date }}</td>
                        <td>{{ $e->venue ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center py-3 text-muted">No upcoming events.</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-chart-pie me-2 text-primary"></i>Quick Links</h6></div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('alumni.index') }}" class="list-group-item list-group-item-action d-flex align-items-center"><i class="fas fa-address-book me-3 text-primary"></i>Alumni Directory</a>
                    <a href="{{ route('alumni.events') }}" class="list-group-item list-group-item-action d-flex align-items-center"><i class="fas fa-calendar-alt me-3 text-info"></i>Alumni Events</a>
                    <a href="{{ route('alumni.donations') }}" class="list-group-item list-group-item-action d-flex align-items-center"><i class="fas fa-hand-holding-usd me-3 text-warning"></i>Alumni Donations</a>
                    <a href="{{ route('alumni.jobs') }}" class="list-group-item list-group-item-action d-flex align-items-center"><i class="fas fa-briefcase me-3 text-danger"></i>Job Board</a>
                    <a href="{{ route('alumni.networking') }}" class="list-group-item list-group-item-action d-flex align-items-center"><i class="fas fa-network-wired me-3 text-success"></i>Networking Platform</a>
                    <a href="{{ route('alumni.reports') }}" class="list-group-item list-group-item-action d-flex align-items-center"><i class="fas fa-chart-bar me-3 text-secondary"></i>Alumni Reports</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection