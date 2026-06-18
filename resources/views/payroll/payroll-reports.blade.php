@extends('layouts.app')
@section('title', 'Payroll Reports')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-chart-line me-2"></i>Payroll Reports</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Payroll</li><li class="breadcrumb-item active">Payroll Reports</li></ol></nav>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center"><div class="fs-3 fw-bold">{{ $totalPayrolls }}</div><small class="text-muted">Total Payrolls</small></div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center"><div class="fs-3 fw-bold text-success">${{ number_format($totalPaid, 2) }}</div><small class="text-muted">Total Paid</small></div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center"><div class="fs-3 fw-bold text-warning">${{ number_format($totalPending, 2) }}</div><small class="text-muted">Total Pending</small></div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center"><div class="fs-3 fw-bold text-primary">${{ number_format($avgSalary, 2) }}</div><small class="text-muted">Avg Net Salary</small></div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-circle me-2"></i>By Status</h6></div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Status</th><th class="text-end">Count</th><th class="text-end">Amount</th></tr></thead>
                    <tbody>
                        @forelse($byStatus as $s)
                        <tr>
                            <td>{{ ucfirst($s->status) }}</td>
                            <td class="text-end"><span class="badge bg-primary bg-opacity-10 text-primary">{{ $s->total }}</span></td>
                            <td class="text-end">${{ number_format($s->amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-3 text-muted">No data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-calendar-alt me-2"></i>Monthly Summary (Last 12)</h6></div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Period</th><th class="text-end">Payrolls</th><th class="text-end">Net Amount</th><th class="text-end">Tax</th><th class="text-end">Bonus</th></tr></thead>
                    <tbody>
                        @forelse($byMonth as $m)
                        <tr>
                            <td>{{ DateTime::createFromFormat('!m', $m->month)->format('F') }} {{ $m->year }}</td>
                            <td class="text-end">{{ $m->total }}</td>
                            <td class="text-end">${{ number_format($m->total_amount, 2) }}</td>
                            <td class="text-end">${{ number_format($m->total_tax, 2) }}</td>
                            <td class="text-end">${{ number_format($m->total_bonus, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-3 text-muted">No data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-chart-bar me-2"></i>Year {{ $currentYear }} Summary</h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded text-center">
                            <div class="fs-4 fw-bold text-success">${{ number_format($yearlyTotal, 2) }}</div>
                            <small class="text-muted">Total Salary Paid</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded text-center">
                            <div class="fs-4 fw-bold text-danger">${{ number_format($yearlyTax, 2) }}</div>
                            <small class="text-muted">Total Tax Deducted</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded text-center">
                            <div class="fs-4 fw-bold text-warning">${{ number_format($yearlyBonus, 2) }}</div>
                            <small class="text-muted">Total Bonuses Paid</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
