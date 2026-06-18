@extends('layouts.app')
@section('title', 'Hostel Reports')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chart-bar me-2"></i>Hostel Reports</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Hostel</li><li class="breadcrumb-item active">Hostel Reports</li></ol></nav>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-2">
        <x-stats-card icon="fa-building" title="Hostels" :value="$totalHostels" color="primary" />
    </div>
    <div class="col-md-2">
        <x-stats-card icon="fa-door-open" title="Rooms" :value="$totalRooms" color="info" />
    </div>
    <div class="col-md-2">
        <x-stats-card icon="fa-bed" title="Beds" :value="$totalBeds" color="success" />
    </div>
    <div class="col-md-2">
        <x-stats-card icon="fa-user-check" title="Active Allocations" :value="$activeAllocations" color="warning" />
    </div>
    <div class="col-md-2">
        <x-stats-card icon="fa-clock" title="Pending Leaves" :value="$pendingLeaves" color="danger" />
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-building me-2 text-primary"></i>Hostel Overview</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Hostel','Rooms','Beds']">
                    @forelse($hostelOccupancy as $ho)
                    <tr>
                        <td class="fw-semibold">{{ $ho->name }}</td>
                        <td>{{ $ho->total_rooms ?? 0 }}</td>
                        <td>{{ $ho->total_beds ?? 0 }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center py-3 text-muted">No hostels configured.</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-bed me-2 text-success"></i>Bed Status Distribution</h6></div>
            <div class="card-body">
                @php $total = $bedStatusCounts->sum('total'); @endphp
                @if($total > 0)
                    @foreach($bedStatusCounts as $bc)
                        @php $pct = round(($bc->total / $total) * 100); $colors = ['available' => 'success', 'occupied' => 'warning', 'maintenance' => 'danger']; @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="badge bg-{{ $colors[$bc->status] ?? 'secondary' }}">{{ ucfirst($bc->status) }}</span>
                                <small class="text-muted">{{ $bc->total }} beds ({{ $pct }}%)</small>
                            </div>
                            <div class="progress" style="height: 10px;"><div class="progress-bar bg-{{ $colors[$bc->status] ?? 'secondary' }}" style="width: {{ $pct }}%"></div></div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center py-3">No bed data available.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-2">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-user-check me-2 text-warning"></i>Recent Active Allocations</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Student','Hostel','Room','Check In']">
                    @forelse($recentAllocations as $ra)
                    <tr>
                        <td class="fw-semibold">{{ $ra->first_name }} {{ $ra->last_name }}</td>
                        <td>{{ $ra->hostel_name }}</td>
                        <td>{{ $ra->room_number ?? '-' }}</td>
                        <td>{{ $ra->check_in_date }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-3 text-muted">No active allocations.</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-calendar-times me-2 text-danger"></i>Leave Status Summary</h6></div>
            <div class="card-body">
                @if($leaveStats->isNotEmpty())
                    @php $totalLeaves = $leaveStats->sum('total'); @endphp
                    @foreach($leaveStats as $ls)
                        @php $pct = round(($ls->total / $totalLeaves) * 100); $colors = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger']; @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="badge bg-{{ $colors[$ls->status] ?? 'secondary' }}">{{ ucfirst($ls->status) }}</span>
                                <small class="text-muted">{{ $ls->total }} requests ({{ $pct }}%)</small>
                            </div>
                            <div class="progress" style="height: 10px;"><div class="progress-bar bg-{{ $colors[$ls->status] ?? 'secondary' }}" style="width: {{ $pct }}%"></div></div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center py-3">No leave data available.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
