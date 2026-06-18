@extends('layouts.app')
@section('title', 'Payroll Detail')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-file-invoice me-2"></i>Payroll Detail</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('payroll.index') }}">Payroll</a></li><li class="breadcrumb-item active">Employee 1 - June 2026</li></ol></nav>
    </div>
    <button class="btn btn-outline-success"><i class="fas fa-download me-1"></i>Download Slip</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-wallet me-2 text-primary"></i>Salary Slip - June 2026</h6></div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <h6 class="fw-semibold border-bottom pb-2 mb-3">Employee Info</h6>
                <div class="mb-2"><small class="text-muted d-block">Name</small><span class="fw-semibold">John Smith</span></div>
                <div class="mb-2"><small class="text-muted d-block">Employee ID</small><span>EMP-0001</span></div>
                <div class="mb-2"><small class="text-muted d-block">Department</small><span>Teaching</span></div>
                <div class="mb-2"><small class="text-muted d-block">Designation</small><span>Senior Teacher</span></div>
            </div>
            <div class="col-md-6">
                <h6 class="fw-semibold border-bottom pb-2 mb-3">Payment Info</h6>
                <div class="mb-2"><small class="text-muted d-block">Bank</small><span>First National Bank</span></div>
                <div class="mb-2"><small class="text-muted d-block">Account No.</small><span>****1234</span></div>
                <div class="mb-2"><small class="text-muted d-block">Payment Method</small><span>Bank Transfer</span></div>
                <div class="mb-2"><small class="text-muted d-block">Payment Date</small><span>Jun 28, 2026</span></div>
            </div>
        </div>
        <hr>
        <div class="row g-3">
            <div class="col-md-6">
                <h6 class="text-success fw-semibold">Earnings</h6>
                <div class="d-flex justify-content-between mb-1"><span>Basic Salary</span><span>$5,000.00</span></div>
                <div class="d-flex justify-content-between mb-1"><span>House Rent Allowance</span><span>$1,500.00</span></div>
                <div class="d-flex justify-content-between mb-1"><span>Travel Allowance</span><span>$300.00</span></div>
                <div class="d-flex justify-content-between mb-1"><span>Medical Allowance</span><span>$400.00</span></div>
                <div class="d-flex justify-content-between mb-1"><span>Dearness Allowance</span><span>$600.00</span></div>
                <hr>
                <div class="d-flex justify-content-between fw-bold"><span>Total Earnings</span><span class="text-success">$7,800.00</span></div>
            </div>
            <div class="col-md-6">
                <h6 class="text-danger fw-semibold">Deductions</h6>
                <div class="d-flex justify-content-between mb-1"><span>Income Tax</span><span>$800.00</span></div>
                <div class="d-flex justify-content-between mb-1"><span>Provident Fund</span><span>$500.00</span></div>
                <div class="d-flex justify-content-between mb-1"><span>Professional Tax</span><span>$150.00</span></div>
                <div class="d-flex justify-content-between mb-1"><span>Loan Deduction</span><span>$200.00</span></div>
                <hr>
                <div class="d-flex justify-content-between fw-bold"><span>Total Deductions</span><span class="text-danger">$1,650.00</span></div>
            </div>
        </div>
        <hr>
        <div class="d-flex justify-content-between">
            <h5 class="fw-bold">Net Pay</h5>
            <h5 class="fw-bold text-primary">$6,150.00</h5>
        </div>
    </div>
</div>
@endsection