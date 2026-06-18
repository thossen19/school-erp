@extends('layouts.app')
@section('title', 'Asset Reports')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chart-pie me-2"></i>Asset Reports</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Asset Management</li><li class="breadcrumb-item active">Reports</li></ol></nav>
    </div>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-2"><x-stats-card icon="fa-boxes" title="Total Assets" :value="$totalAssets" color="primary" /></div>
    <div class="col-md-2"><x-stats-card icon="fa-dollar-sign" title="Total Value" :value="number_format($totalValue, 0)" color="success" /></div>
    <div class="col-md-2"><x-stats-card icon="fa-hand-holding" title="Allocated" :value="$allocated" color="info" /></div>
    <div class="col-md-2"><x-stats-card icon="fa-tools" title="Pending Maint." :value="$pendingMaintenance" color="warning" /></div>
    <div class="col-md-2"><x-stats-card icon="fa-chart-line" title="Total Depreciation" :value="number_format($totalDepreciation, 0)" color="danger" /></div>
    <div class="col-md-2"><x-stats-card icon="fa-exclamation-triangle" title="Missing" :value="$missingAssets" color="danger" /></div>
</div>
<div class="row g-3">
    <div class="col-md-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-flag me-2 text-primary"></i>Status Breakdown</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Status','Count']">
                    @forelse($statusBreakdown as $s)
                    <tr>
                        <td><span class="badge bg-{{ $s->status === 'active' ? 'success' : ($s->status === 'maintenance' ? 'warning' : ($s->status === 'missing' ? 'danger' : 'secondary')) }}">{{ ucfirst($s->status) }}</span></td>
                        <td><span class="badge bg-primary">{{ $s->total }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-center py-3 text-muted">No data</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-layer-group me-2 text-success"></i>Category Breakdown</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Category','Count','Total Value']">
                    @forelse($categoryBreakdown as $cb)
                    <tr>
                        <td class="fw-semibold">{{ $cb->category_name }}</td>
                        <td><span class="badge bg-info">{{ $cb->total }}</span></td>
                        <td class="fw-bold">{{ number_format($cb->total_value, 0) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center py-3 text-muted">No data</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
</div>
<div class="row g-3 mt-3">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-clipboard-list me-2 text-secondary"></i>Recent Audits</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Date','Asset','Condition','Missing']">
                    @forelse($recentAudits as $ra)
                    <tr>
                        <td>{{ $ra->audit_date }}</td>
                        <td class="fw-semibold">{{ $ra->asset_name }} ({{ $ra->asset_code }})</td>
                        <td><span class="badge bg-{{ $ra->condition === 'good' ? 'success' : ($ra->condition === 'fair' ? 'warning' : 'danger') }}">{{ ucfirst($ra->condition ?? 'N/A') }}</span></td>
                        <td>@if($ra->is_missing)<span class="badge bg-danger">Yes</span>@else<span class="badge bg-success">No</span>@endif</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-3 text-muted">No audits yet.</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
</div>
@endsection