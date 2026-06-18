@extends('layouts.app')
@section('title', 'Attendance Analytics')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-calendar-check me-2"></i>Attendance Analytics</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">MIS Reports</li><li class="breadcrumb-item active">Attendance Analytics</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3"><label class="form-label fw-semibold small">From</label><input type="date" name="date_from" class="form-control form-control-sm" value="{{ $dateFrom }}"></div>
            <div class="col-md-3"><label class="form-label fw-semibold small">To</label><input type="date" name="date_to" class="form-control form-control-sm" value="{{ $dateTo }}"></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button></div>
        </form>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-chart-bar me-2 text-primary"></i>Attendance Summary</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Status','Count']">
                    @forelse($summary as $s)
                    <tr>
                        <td><span class="badge bg-{{ $s->status === 'present' ? 'success' : ($s->status === 'absent' ? 'danger' : ($s->status === 'late' ? 'warning' : 'info')) }}">{{ ucfirst($s->status) }}</span></td>
                        <td class="fw-bold">{{ $s->count }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-center py-3 text-muted">No attendance data</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-layer-group me-2 text-success"></i>Class-wise Attendance</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Class','Total','Present','Rate']">
                    @forelse($classWiseAttendance as $c)
                    @php $rate = $c->total > 0 ? round(($c->present / $c->total) * 100, 1) : 0; @endphp
                    <tr>
                        <td class="fw-semibold">{{ $c->name }}</td>
                        <td>{{ $c->total }}</td>
                        <td>{{ $c->present }}</td>
                        <td><span class="badge bg-{{ $rate >= 90 ? 'success' : ($rate >= 75 ? 'warning' : 'danger') }}">{{ $rate }}%</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-3 text-muted">No data</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
</div>
<div class="row g-3 mt-3">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-chart-line me-2 text-info"></i>Daily Attendance Trend</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Date','Total','Present','Rate']">
                    @forelse($dailyTrend as $d)
                    @php $rate = $d->total > 0 ? round(($d->present / $d->total) * 100, 1) : 0; @endphp
                    <tr>
                        <td>{{ $d->date }}</td>
                        <td>{{ $d->total }}</td>
                        <td>{{ $d->present }}</td>
                        <td><span class="badge bg-{{ $rate >= 90 ? 'success' : ($rate >= 75 ? 'warning' : 'danger') }}">{{ $rate }}%</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-3 text-muted">No daily trend data</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
</div>
@endsection