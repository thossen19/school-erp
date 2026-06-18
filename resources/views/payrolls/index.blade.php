@extends('layouts.app')
@section('title', 'Payroll')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-wallet me-2"></i>Payroll Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Payroll</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('payroll.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Process Payroll</a>
        <a href="{{ route('payroll.salary-structures') }}" class="btn btn-outline-info"><i class="fas fa-cog me-1"></i>Salary Structures</a>
        <a href="{{ route('payroll.loans') }}" class="btn btn-outline-warning"><i class="fas fa-hand-holding-usd me-1"></i>Loans</a>
    </div>
</div>

<div class="filter-bar">
    <div class="row g-2 align-items-end">
        <div class="col-md-2"><label class="form-label fw-semibold small">Month</label><select class="form-select form-select-sm"><option>June 2026</option><option>May 2026</option></select></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Department</label><select class="form-select form-select-sm"><option>All</option><option>Teaching</option></select></div>
        <div class="col-md-2"><label class="form-label fw-semibold small">Status</label><select class="form-select form-select-sm"><option>All</option><option>Paid</option><option>Pending</option></select></div>
        <div class="col-md-1"><button class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button></div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><x-stats-card title="Total Payroll" value="$185,000" icon="fa-wallet" color="primary" /></div>
    <div class="col-md-3"><x-stats-card title="Employees" value="96" icon="fa-users" color="info" /></div>
    <div class="col-md-3"><x-stats-card title="Paid" value="$155,000" icon="fa-check-circle" color="success" /></div>
    <div class="col-md-3"><x-stats-card title="Pending" value="$30,000" icon="fa-clock" color="warning" /></div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Employee','Department','Basic Salary','Allowances','Deductions','Net Pay','Status','Actions']">
            @foreach(range(1,10) as $i)
            @php $basic = rand(3000,8000); $allow = rand(500,2000); $deduct = rand(200,1000); $net = $basic+$allow-$deduct; @endphp
            <tr>
                <td>{{ $i }}</td>
                <td><a href="#" class="text-decoration-none fw-semibold">Employee {{ $i }}</a></td>
                <td>{{ ['Teaching','Admin','Accounts','Library'][$i%4] }}</td>
                <td>${{ number_format($basic,2) }}</td>
                <td>${{ number_format($allow,2) }}</td>
                <td>${{ number_format($deduct,2) }}</td>
                <td class="fw-bold text-primary">${{ number_format($net,2) }}</td>
                <td><span class="badge bg-{{ $i%3==0?'warning':'success' }}">{{ $i%3==0?'Pending':'Paid' }}</span></td>
                <td>
                    <div class="table-actions">
                        <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-sm btn-outline-success"><i class="fas fa-download"></i> Slip</button>
                    </div>
                </td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
@endsection