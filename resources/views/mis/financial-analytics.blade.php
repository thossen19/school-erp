@extends('layouts.app')
@section('title', 'Financial Analytics')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-chart-pie me-2"></i>Financial Analytics</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">MIS Reports</li><li class="breadcrumb-item active">Financial Analytics</li></ol></nav>
    </div>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-2">
        <x-stats-card icon="fa-money-bill-wave" title="Collected ({{ $year }})" :value="number_format($totalCollected, 0)" color="success" />
    </div>
    <div class="col-md-2">
        <x-stats-card icon="fa-hourglass-half" title="Pending" :value="number_format($totalPending, 0)" color="danger" />
    </div>
</div>
<div class="row g-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-calendar-alt me-2 text-primary"></i>Monthly Collection ({{ $year }})</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Month','Collected']">
                    @forelse($monthlyCollection as $m)
                    <tr>
                        <td class="fw-semibold">{{ date('F', mktime(0, 0, 0, $m->month, 1)) }}</td>
                        <td class="fw-bold">{{ number_format($m->total, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-center py-3 text-muted">No data for {{ $year }}</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-credit-card me-2 text-warning"></i>Payment Mode Breakdown</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Mode','Transactions','Total']">
                    @forelse($paymentModeBreakdown as $p)
                    <tr>
                        <td>{{ $p->payment_mode ?? 'N/A' }}</td>
                        <td><span class="badge bg-info">{{ $p->count }}</span></td>
                        <td class="fw-bold">{{ number_format($p->total, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center py-3 text-muted">No payment data</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
</div>
<div class="row g-3 mt-3">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="fas fa-tags me-2 text-secondary"></i>Fee Head-wise Collection</h6></div>
            <div class="card-body p-0">
                <x-table :headers="['Fee Head','Amount']">
                    @forelse($feeHeadWise as $fh)
                    <tr>
                        <td class="fw-semibold">{{ $fh->head_name }}</td>
                        <td class="fw-bold">{{ number_format($fh->total, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-center py-3 text-muted">No data</td></tr>
                    @endforelse
                </x-table>
            </div>
        </div>
    </div>
</div>
@endsection