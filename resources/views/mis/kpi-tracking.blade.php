@extends('layouts.app')
@section('title', 'KPI Tracking')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-bullseye me-2"></i>KPI Tracking</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">MIS Reports</li><li class="breadcrumb-item active">KPI Tracking</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['KPI Metric','Current Value','Target','Achievement','Progress']">
            @forelse($kpis as $k)
            @php $pct = $k['target'] > 0 ? min(round(($k['value'] / $k['target']) * 100, 1), 100) : 0; @endphp
            <tr>
                <td class="fw-semibold">{{ $k['metric'] }}</td>
                <td class="fw-bold">{{ $k['value'] }}{{ $k['unit'] }}</td>
                <td>{{ $k['target'] }}{{ $k['unit'] }}</td>
                <td><span class="badge bg-{{ $pct >= 80 ? 'success' : ($pct >= 50 ? 'warning' : 'danger') }}">{{ $pct }}%</span></td>
                <td style="min-width:150px">
                    <div class="progress" style="height:12px">
                        <div class="progress-bar bg-{{ $pct >= 80 ? 'success' : ($pct >= 50 ? 'warning' : 'danger') }}" style="width:{{ $pct }}%">{{ $pct }}%</div>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center py-4 text-muted">No KPI data available.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
@endsection