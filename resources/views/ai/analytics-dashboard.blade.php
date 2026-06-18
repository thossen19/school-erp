@extends('layouts.app')
@section('title', 'AI Analytics Dashboard')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chart-pie me-2"></i>AI Analytics Dashboard</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">AI</a></li><li class="breadcrumb-item active">Analytics Dashboard</li></ol></nav>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <x-stats-card icon="fa-users" value="{{ $totalStudents ?? 0 }}" title="Total Students" color="primary" />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stats-card icon="fa-chalkboard-user" value="{{ $totalTeachers ?? 0 }}" title="Total Teachers" color="success" />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stats-card icon="fa-calendar-check" value="{{ $avgAttendance ?? 0 }}%" title="Avg Attendance" color="info" />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-stats-card icon="fa-coins" value="৳{{ number_format($feeCollection ?? 0, 2) }}" title="Fee Collection" color="warning" />
    </div>
</div>

<div class="row g-3">
    <div class="col-xl-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-robot me-2"></i>AI Predictions Overview</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Prediction Type','Average Score','Total']">
                    @forelse($predictions ?? [] as $p)
                    <tr>
                        <td class="fw-semibold">{{ ucfirst(str_replace('_', ' ', $p->type ?? $p['type'] ?? 'N/A')) }}</td>
                        <td>
                            <span class="badge bg-{{ ($p->avg_score ?? $p['avg_score'] ?? 0) >= 70 ? 'success' : (($p->avg_score ?? $p['avg_score'] ?? 0) >= 40 ? 'warning' : 'danger') }}">
                                {{ number_format($p->avg_score ?? $p['avg_score'] ?? 0, 1) }}%
                            </span>
                        </td>
                        <td>{{ $p->total ?? $p['total'] ?? 0 }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center text-muted py-4">No predictions available</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-file-alt me-2"></i>Recent AI Reports</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Title','Type','Status','Generated At']">
                    @forelse($recentReports ?? [] as $r)
                    <tr>
                        <td class="fw-semibold">{{ $r->title ?? $r['title'] ?? 'Untitled' }}</td>
                        <td><span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $r->type ?? $r['type'] ?? 'custom')) }}</span></td>
                        <td>
                            @php
                                $s = $r->status ?? $r['status'] ?? 'pending';
                                $sc = $s == 'completed' ? 'success' : ($s == 'processing' ? 'warning' : 'secondary');
                            @endphp
                            <span class="badge bg-{{ $sc }}">{{ ucfirst($s) }}</span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($r->created_at ?? $r['created_at'] ?? now())->format('M d, Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">No reports yet</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
</div>
@endsection
