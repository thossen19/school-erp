@extends('layouts.app')
@section('title', 'Payroll Processing')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-file-invoice-dollar me-2"></i>Payroll Processing</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Payroll</a></li><li class="breadcrumb-item active">Processing</li></ol></nav>
    </div>
    <a href="#" class="btn btn-primary btn-sm">+ New Payroll</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Employee','Period','Gross Salary','Deductions','Net Salary','Payment Date','Status']">
            @forelse($payrolls as $p)
            <tr>
                <td class="fw-semibold">{{ $p->first_name }} {{ $p->last_name }}<br><small class="text-muted">{{ $p->employee_no }}</small></td>
                <td>{{ $p->payroll_period ?? $p->month . '/' . $p->year }}</td>
                <td>{{ number_format($p->gross_salary ?? $p->total_earnings, 2) }}</td>
                <td>{{ number_format($p->total_deductions, 2) }}</td>
                <td class="fw-bold">{{ number_format($p->net_salary, 2) }}</td>
                <td>{{ $p->payment_date ? \Carbon\Carbon::parse($p->payment_date)->format('d-m-Y') : '-' }}</td>
                <td>
                    @php $b = match($p->status) { 'paid' => 'success', 'processing' => 'warning', 'pending' => 'secondary', 'cancelled' => 'danger', default => 'secondary' }; @endphp
                    <span class="badge bg-{{ $b }}">{{ ucfirst($p->status) }}</span>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center text-muted py-3">No payroll records found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$payrolls" />
</div>
@endsection
