@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-bold mb-0">Dashboard</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route_if_exists('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary btn-sm" onclick="window.location.reload()">
            <i class="fas fa-sync-alt me-1"></i>Refresh
        </button>
    </div>
</div>

{{-- Top Stats Row --}}
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <x-stats-card icon="fa-users" value="{{ $data['totalStudents'] ?? 0 }}" title="Total Students" color="primary" />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stats-card icon="fa-user-graduate" value="{{ $data['newAdmissions'] ?? 0 }}" title="New Admissions" color="success" />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stats-card icon="fa-money-bill-wave" value="৳{{ number_format($data['totalFeesCollected'] ?? 0, 2) }}" title="Fee Collected" color="warning" />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stats-card icon="fa-calendar-check" value="{{ $data['todayAttendancePct'] ?? 0 }}%" title="Today Attendance" color="info" />
    </div>
</div>

<div class="row g-3">
    {{-- Student Statistics --}}
    <div class="col-xl-6 col-lg-12">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                <h6 class="fw-semibold mb-0"><i class="fas fa-users me-2"></i>Student Statistics</h6>
                <span class="badge bg-primary">Active: {{ $data['activeStudents'] ?? 0 }}</span>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach($data['studentGenderData'] ?? [] as $item)
                    <div class="col-6 text-center">
                        <h3 class="fw-bold text-{{ $item['color'] }} mb-0">{{ $item['value'] }}</h3>
                        <small class="text-muted">{{ $item['label'] }}</small>
                    </div>
                    @endforeach
                </div>
                @if(($data['classWiseStudents'] ?? collect())->isNotEmpty())
                <hr>
                <h6 class="fw-semibold small">Class-wise Distribution</h6>
                @foreach($data['classWiseStudents'] as $c)
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <small>{{ $c->name }}</small>
                    <small class="fw-bold">{{ $c->total }}</small>
                </div>
                <div class="progress mb-2" style="height:6px">
                    <div class="progress-bar" style="width:{{ ($c->total / max($data['totalStudents'],1)) * 100 }}%"></div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>

    {{-- Admission Statistics --}}
    <div class="col-xl-3 col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-clipboard-list me-2"></i>Admissions</h6></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2"><small>Enquiries</small><span class="fw-bold">{{ $data['totalEnquiries'] ?? 0 }}</span></div>
                <div class="d-flex justify-content-between mb-2"><small>Pending</small><span class="fw-bold text-warning">{{ $data['pendingEnquiries'] ?? 0 }}</span></div>
                <div class="d-flex justify-content-between mb-2"><small>Converted</small><span class="fw-bold text-success">{{ $data['convertedEnquiries'] ?? 0 }}</span></div>
                <hr>
                <div class="d-flex justify-content-between mb-2"><small>Forms Submitted</small><span class="fw-bold">{{ $data['totalForms'] ?? 0 }}</span></div>
                <div class="d-flex justify-content-between mb-2"><small>Approved</small><span class="fw-bold text-success">{{ $data['approvedForms'] ?? 0 }}</span></div>
                <div class="d-flex justify-content-between"><small>Admitted</small><span class="fw-bold text-primary">{{ $data['admittedForms'] ?? 0 }}</span></div>
            </div>
        </div>
    </div>

    {{-- Attendance Analytics --}}
    <div class="col-xl-3 col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-calendar-check me-2"></i>Attendance Analytics</h6></div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <h2 class="fw-bold text-{{ $data['todayAttendancePct'] >= 75 ? 'success' : ($data['todayAttendancePct'] >= 50 ? 'warning' : 'danger') }} mb-0">{{ $data['todayAttendancePct'] }}%</h2>
                    <small class="text-muted">Today's Attendance</small>
                </div>
                <div class="d-flex justify-content-between mb-1"><small>Present</small><span class="fw-bold text-success">{{ $data['todayPresent'] ?? 0 }}</span></div>
                <div class="d-flex justify-content-between"><small>Total</small><span class="fw-bold">{{ $data['todayAttendance'] ?? 0 }}</span></div>
                <hr>
                <h6 class="fw-semibold small">Last 7 Days</h6>
                @foreach($data['weeklyAttendance'] ?? [] as $w)
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <small>{{ \Carbon\Carbon::parse($w->date)->format('D') }}</small>
                    <div class="flex-grow-1 mx-2">
                        <div class="progress" style="height:5px">
                            <div class="progress-bar bg-success" style="width:{{ $w->total > 0 ? ($w->present/$w->total)*100 : 0 }}%"></div>
                        </div>
                    </div>
                    <small class="fw-bold">{{ $w->total > 0 ? round(($w->present/$w->total)*100) : 0 }}%</small>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    {{-- Fee Collection Reports --}}
    <div class="col-xl-4 col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                <h6 class="fw-semibold mb-0"><i class="fas fa-money-bill-wave me-2"></i>Fee Collection</h6>
                <span class="badge bg-danger">Pending: ৳{{ number_format($data['pendingDues'] ?? 0, 2) }}</span>
            </div>
            <div class="card-body">
                <h3 class="fw-bold text-success mb-0">৳{{ number_format($data['totalFeesCollected'] ?? 0, 2) }}</h3>
                <small class="text-muted">Total Collected</small>
                <hr>
                <h6 class="fw-semibold small">Monthly Collection ({{ now()->year }})</h6>
                @foreach($data['monthlyFees'] ?? [] as $m)
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <small>{{ Carbon\Carbon::create()->month($m->month)->format('M') }}</small>
                    <div class="flex-grow-1 mx-2">
                        <div class="progress" style="height:5px">
                            <div class="progress-bar bg-warning" style="width:{{ $m->total > 0 ? min(($m->total/max($data['totalFeesCollected'],1))*100,100) : 0 }}%"></div>
                        </div>
                    </div>
                    <small class="fw-bold">৳{{ number_format($m->total, 0) }}</small>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Payroll Summary --}}
    <div class="col-xl-4 col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                <h6 class="fw-semibold mb-0"><i class="fas fa-wallet me-2"></i>Payroll Summary</h6>
                <span class="badge bg-{{ ($data['pendingPayroll'] ?? 0) > 0 ? 'warning' : 'success' }}">{{ ($data['pendingPayroll'] ?? 0) > 0 ? 'Pending' : 'Cleared' }}</span>
            </div>
            <div class="card-body">
                <div class="row g-2 text-center mb-2">
                    <div class="col-6"><h4 class="fw-bold text-primary mb-0">৳{{ number_format(optional($data['currentPayroll'])->total ?? 0, 2) }}</h4><small class="text-muted">Current Month</small></div>
                    <div class="col-6"><h4 class="fw-bold text-info mb-0">{{ optional($data['currentPayroll'])->count ?? 0 }}</h4><small class="text-muted">Employees</small></div>
                </div>
                @if(($data['pendingPayroll'] ?? 0) > 0)
                <div class="alert alert-warning py-1 px-2 mb-2 small">৳{{ number_format($data['pendingPayroll'], 2) }} in pending payments</div>
                @endif
                <h6 class="fw-semibold small">Monthly Payroll ({{ now()->year }})</h6>
                @foreach($data['monthlyPayroll'] ?? [] as $p)
                <div class="d-flex justify-content-between mb-1">
                    <small>{{ Carbon\Carbon::create()->month($p->month)->format('M') }}</small>
                    <small class="fw-bold">৳{{ number_format($p->total, 0) }}</small>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Academic Performance --}}
    <div class="col-xl-4 col-lg-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-graduation-cap me-2"></i>Academic Performance</h6></div>
            <div class="card-body text-center">
                <div class="row g-2">
                    <div class="col-4">
                        <h3 class="fw-bold text-primary mb-0">{{ $data['totalExams'] ?? 0 }}</h3>
                        <small class="text-muted">Exams</small>
                    </div>
                    <div class="col-4">
                        <h3 class="fw-bold text-{{ ($data['avgPercentage'] ?? 0) >= 60 ? 'success' : 'warning' }} mb-0">{{ number_format($data['avgPercentage'] ?? 0, 1) }}%</h3>
                        <small class="text-muted">Avg Score</small>
                    </div>
                    <div class="col-4">
                        <h3 class="fw-bold text-{{ ($data['passRate'] ?? 0) >= 75 ? 'success' : 'danger' }} mb-0">{{ $data['passRate'] ?? 0 }}%</h3>
                        <small class="text-muted">Pass Rate</small>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="progress" style="height:12px">
                        <div class="progress-bar bg-{{ ($data['passRate'] ?? 0) >= 75 ? 'success' : 'danger' }}" style="width:{{ min($data['passRate'] ?? 0, 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    {{-- Transport Tracking --}}
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-bus me-2"></i>Transport</h6></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2"><small>Vehicles</small><span class="fw-bold">{{ $data['totalVehicles'] ?? 0 }}</span></div>
                <div class="d-flex justify-content-between mb-2"><small>Active</small><span class="fw-bold text-success">{{ $data['activeVehicles'] ?? 0 }}</span></div>
                <div class="d-flex justify-content-between mb-2"><small>Routes</small><span class="fw-bold">{{ $data['totalRoutes'] ?? 0 }}</span></div>
                <div class="d-flex justify-content-between"><small>Allocations</small><span class="fw-bold text-info">{{ $data['transportAllocations'] ?? 0 }}</span></div>
            </div>
        </div>
    </div>

    {{-- Inventory Status --}}
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-boxes me-2"></i>Inventory</h6></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2"><small>Total Items</small><span class="fw-bold">{{ $data['totalItems'] ?? 0 }}</span></div>
                <div class="d-flex justify-content-between mb-2"><small>Low Stock</small><span class="fw-bold text-{{ ($data['lowStockItems'] ?? 0) > 0 ? 'danger' : 'success' }}">{{ $data['lowStockItems'] ?? 0 }}</span></div>
                <div class="d-flex justify-content-between"><small>Stock Value</small><span class="fw-bold">৳{{ number_format($data['totalStockValue'] ?? 0, 2) }}</span></div>
            </div>
        </div>
    </div>

    {{-- Library Status --}}
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-book me-2"></i>Library</h6></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2"><small>Total Books</small><span class="fw-bold">{{ $data['totalBooks'] ?? 0 }}</span></div>
                <div class="d-flex justify-content-between mb-2"><small>Available</small><span class="fw-bold text-success">{{ $data['availableBooks'] ?? 0 }}</span></div>
                <div class="d-flex justify-content-between mb-2"><small>Issued</small><span class="fw-bold text-warning">{{ $data['issuedBooks'] ?? 0 }}</span></div>
                <div class="d-flex justify-content-between"><small>Members</small><span class="fw-bold text-info">{{ $data['libraryMembers'] ?? 0 }}</span></div>
            </div>
        </div>
    </div>

    {{-- Hostel Occupancy --}}
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-bed me-2"></i>Hostel Occupancy</h6></div>
            <div class="card-body text-center">
                <h3 class="fw-bold text-{{ ($data['hostelOccupancyPct'] ?? 0) > 90 ? 'danger' : (($data['hostelOccupancyPct'] ?? 0) > 70 ? 'warning' : 'success') }} mb-0">{{ $data['hostelOccupancyPct'] ?? 0 }}%</h3>
                <small class="text-muted">Occupancy Rate</small>
                <div class="progress mt-2" style="height:8px">
                    <div class="progress-bar bg-{{ ($data['hostelOccupancyPct'] ?? 0) > 90 ? 'danger' : (($data['hostelOccupancyPct'] ?? 0) > 70 ? 'warning' : 'success') }}" style="width:{{ min($data['hostelOccupancyPct'] ?? 0, 100) }}%"></div>
                </div>
                <div class="d-flex justify-content-between mt-2">
                    <small>Occupied</small><small class="fw-bold">{{ $data['occupiedBeds'] ?? 0 }}</small>
                </div>
                <div class="d-flex justify-content-between">
                    <small>Total Beds</small><small class="fw-bold">{{ $data['totalBeds'] ?? 0 }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    {{-- Upcoming Events --}}
    <div class="col-xl-4 col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-calendar-alt me-2"></i>Upcoming Events</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Event','Date','Venue']">
                    @forelse($data['upcomingEvents'] ?? [] as $e)
                    <tr>
                        <td class="fw-semibold">{{ $e->title }}</td>
                        <td>{{ \Carbon\Carbon::parse($e->start_date)->format('M d, Y') }}</td>
                        <td>{{ $e->venue ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center text-muted py-3">No upcoming events</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>

    {{-- Notifications --}}
    <div class="col-xl-4 col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                <h6 class="fw-semibold mb-0"><i class="fas fa-bell me-2"></i>Notifications</h6>
                @if(($data['unreadCount'] ?? 0) > 0)
                <span class="badge bg-danger rounded-pill">{{ $data['unreadCount'] }} new</span>
                @endif
            </div>
            <div class="card-body p-0">
                <x-table :headers="['Notification','Date']">
                    @forelse($data['notifications'] ?? [] as $n)
                    <tr class="{{ $n->read_at ? '' : 'table-active' }}">
                        <td>
                            <span class="fw-semibold">{{ is_array(json_decode($n->data, true)) ? (json_decode($n->data, true)['title'] ?? 'Notification') : 'Notification' }}</span>
                            <br><small class="text-muted">{{ is_array(json_decode($n->data, true)) ? \Str::limit(json_decode($n->data, true)['body'] ?? '', 50) : '' }}</small>
                        </td>
                        <td><small>{{ \Carbon\Carbon::parse($n->created_at)->diffForHumans() }}</small></td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-center text-muted py-3">No notifications</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>

    {{-- AI Insights --}}
    <div class="col-xl-4 col-md-12">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-robot me-2"></i>AI Insights</h6></div>
            <div class="card-body">
                @forelse($data['aiInsights'] ?? [] as $insight)
                <div class="d-flex align-items-start mb-3">
                    <div class="flex-shrink-0 me-2">
                        <span class="badge bg-{{ $insight['color'] }} p-2"><i class="fas {{ $insight['icon'] }}"></i></span>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-0 small">{{ $insight['title'] }}</h6>
                        <small class="text-muted">{{ $insight['text'] }}</small>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-3"><i class="fas fa-check-circle fa-2x mb-2"></i><p>No insights available</p></div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection