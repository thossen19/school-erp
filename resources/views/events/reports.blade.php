@extends('layouts.app')
@section('title', 'Event Reports')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chart-bar me-2"></i>Event Reports</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('events.index') }}">Events</a></li><li class="breadcrumb-item active">Reports</li></ol></nav>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4 col-6"><div class="card shadow-sm border-0 text-center h-100"><div class="card-body"><h3 class="fw-bold text-primary mb-0">{{ $totalEvents }}</h3><small class="text-muted">Total Events</small></div></div></div>
    <div class="col-md-4 col-6"><div class="card shadow-sm border-0 text-center h-100"><div class="card-body"><h3 class="fw-bold text-success mb-0">{{ $upcomingEvents }}</h3><small class="text-muted">Upcoming Events</small></div></div></div>
    <div class="col-md-4 col-6"><div class="card shadow-sm border-0 text-center h-100"><div class="card-body"><h3 class="fw-bold text-info mb-0">{{ $totalRegistrations }}</h3><small class="text-muted">Total Registrations</small></div></div></div>
    <div class="col-md-4 col-6"><div class="card shadow-sm border-0 text-center h-100"><div class="card-body"><h3 class="fw-bold text-success mb-0">{{ $totalAttended }}</h3><small class="text-muted">Attended</small></div></div></div>
    <div class="col-md-4 col-6"><div class="card shadow-sm border-0 text-center h-100"><div class="card-body"><h3 class="fw-bold text-warning mb-0">{{ $totalClubs }}</h3><small class="text-muted">Active Clubs</small></div></div></div>
    <div class="col-md-4 col-6"><div class="card shadow-sm border-0 text-center h-100"><div class="card-body"><h3 class="fw-bold text-purple mb-0">{{ $totalClubMembers }}</h3><small class="text-muted">Club Members</small></div></div></div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0">Events by Type</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Type','Count']">
                    @forelse($eventsByType as $item)
                    <tr>
                        <td><span class="badge bg-info">{{ ucfirst($item->event_type) }}</span></td>
                        <td class="fw-bold">{{ $item->total }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-muted text-center py-3">No data</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0">Events by Status</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Status','Count']">
                    @forelse($eventsByStatus as $item)
                    <tr>
                        <td><span class="badge bg-{{ $item->status=='completed'?'success':($item->status=='ongoing'?'warning':($item->status=='cancelled'?'danger':'primary')) }}">{{ ucfirst($item->status) }}</span></td>
                        <td class="fw-bold">{{ $item->total }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-muted text-center py-3">No data</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0">Monthly Events ({{ now()->year }})</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Month','Count']">
                    @forelse($monthlyEvents as $item)
                    <tr>
                        <td>{{ Carbon\Carbon::create()->month($item->month)->format('F') }}</td>
                        <td class="fw-bold">{{ $item->total }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-muted text-center py-3">No data</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
</div>
@endsection