@extends('layouts.app')
@section('title', 'Reception Dashboard')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-concierge-bell me-2"></i>Reception Dashboard</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Reception Dashboard</li></ol></nav>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-2 col-4"><div class="card shadow-sm border-0 text-center h-100"><div class="card-body"><h3 class="fw-bold text-primary mb-0">{{ $todayVisitors }}</h3><small class="text-muted">Today's Visitors</small></div></div></div>
    <div class="col-md-2 col-4"><div class="card shadow-sm border-0 text-center h-100"><div class="card-body"><h3 class="fw-bold text-info mb-0">{{ $pendingAppointments }}</h3><small class="text-muted">Pending Appts</small></div></div></div>
    <div class="col-md-2 col-4"><div class="card shadow-sm border-0 text-center h-100"><div class="card-body"><h3 class="fw-bold text-warning mb-0">{{ $openEnquiries }}</h3><small class="text-muted">Open Enquiries</small></div></div></div>
    <div class="col-md-2 col-4"><div class="card shadow-sm border-0 text-center h-100"><div class="card-body"><h3 class="fw-bold text-danger mb-0">{{ $openComplaints }}</h3><small class="text-muted">Open Complaints</small></div></div></div>
    <div class="col-md-2 col-4"><div class="card shadow-sm border-0 text-center h-100"><div class="card-body"><h3 class="fw-bold text-success mb-0">{{ $todayCalls }}</h3><small class="text-muted">Today's Calls</small></div></div></div>
    <div class="col-md-2 col-4"><div class="card shadow-sm border-0 text-center h-100"><div class="card-body"><h3 class="fw-bold text-purple mb-0">{{ $todayVisitors + $todayCalls }}</h3><small class="text-muted">Total Interactions</small></div></div></div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                <h6 class="fw-semibold mb-0"><i class="fas fa-users me-2"></i>Recent Visitors</h6>
                <a href="{{ route('front-office.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <x-table :headers="['Name','Phone','Purpose','Check In']">
                    @forelse($recentVisitors as $v)
                    <tr>
                        <td class="fw-semibold">{{ $v->name }}</td>
                        <td>{{ $v->phone }}</td>
                        <td>{{ \Str::limit($v->purpose, 20) }}</td>
                        <td>{{ \Carbon\Carbon::parse($v->check_in)->format('h:i A') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-muted text-center py-3">No visitors today</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                <h6 class="fw-semibold mb-0"><i class="fas fa-calendar-check me-2"></i>Today's Appointments</h6>
                <a href="{{ route('front-office.appointments') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <x-table :headers="['Visitor','Time','Purpose','Status']">
                    @forelse($todayAppointments as $a)
                    <tr>
                        <td class="fw-semibold">{{ $a->visitor_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($a->time)->format('h:i A') }}</td>
                        <td>{{ \Str::limit($a->purpose, 20) }}</td>
                        <td><span class="badge bg-{{ $a->status=='confirmed'?'success':($a->status=='completed'?'secondary':($a->status=='cancelled'?'danger':'warning')) }}">{{ ucfirst($a->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-muted text-center py-3">No appointments today</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
</div>
@endsection