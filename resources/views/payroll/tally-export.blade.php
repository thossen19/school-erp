@extends('layouts.app')
@section('title', 'Tally Export')
@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-file-export me-2"></i>Tally Export</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Payroll</li><li class="breadcrumb-item active">Tally Export</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Year</label>
                <select name="year" class="form-select form-select-sm">
                    <option value="">All Years</option>
                    @foreach($years as $y)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small">Month</label>
                <select name="month" class="form-select form-select-sm">
                    <option value="">All Months</option>
                    @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-filter"></i></button>
            </div>
            @if(request()->anyFilled(['year','month']))
            <div class="col-12"><a href="{{ route('payroll.tally-export') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-times me-1"></i>Clear</a></div>
            @endif
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-semibold mb-0"><i class="fas fa-list me-2"></i>Payroll Records for Export</h6>
        <button class="btn btn-success btn-sm" onclick="alert('Tally XML export will be generated for selected records.')"><i class="fas fa-file-export me-1"></i>Export to Tally</button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th><input type="checkbox" id="selectAll"></th><th>Employee</th><th>Period</th><th>Net Salary</th><th>Tax</th><th>Bonus</th><th>Payment Date</th></tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $p)
                    <tr>
                        <td><input type="checkbox" class="export-check" value="{{ $p->id }}"></td>
                        <td class="fw-semibold">{{ $p->employee->full_name ?? 'N/A' }}<br><small class="text-muted">{{ $p->employee->employee_no ?? '' }}</small></td>
                        <td>{{ DateTime::createFromFormat('!m', $p->month)->format('F') }} {{ $p->year }}</td>
                        <td class="fw-bold">${{ number_format($p->net_salary, 2) }}</td>
                        <td>${{ number_format($p->tax_amount, 2) }}</td>
                        <td>${{ number_format($p->bonus_amount, 2) ?? '0.00' }}</td>
                        <td>{{ $p->payment_date ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No paid payroll records found for selected period.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<x-pagination :paginator="$payrolls" />
@endsection
@section('scripts')
<script>
document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('.export-check').forEach(cb => cb.checked = this.checked);
});
</script>
@endsection
