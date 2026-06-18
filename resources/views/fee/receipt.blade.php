@extends('layouts.app')
@section('title', 'Fee Receipt')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-receipt me-2"></i>Fee Receipt</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('fees.index') }}">Fees</a></li><li class="breadcrumb-item"><a href="{{ route('fees.collection') }}">Collections</a></li><li class="breadcrumb-item active">Receipt RCT-00001</li></ol></nav>
    </div>
    <button class="btn btn-outline-primary" onclick="window.print()"><i class="fas fa-print me-1"></i>Print</button>
</div>

<div class="card shadow-sm border-0" id="receipt">
    <div class="card-body p-4">
        <div class="text-center border-bottom pb-3 mb-3">
            <h4 class="fw-bold">Springfield International School</h4>
            <p class="mb-0">123 Education Lane, Springfield</p>
            <p>Phone: +1-555-0000 | Email: info@school.edu</p>
            <h5 class="fw-bold mt-3 border p-2 d-inline-block">FEE RECEIPT</h5>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <small class="text-muted d-block">Receipt No.</small>
                <span class="fw-semibold">RCT-00001</span>
            </div>
            <div class="col-md-6 text-end">
                <small class="text-muted d-block">Date</small>
                <span class="fw-semibold">Jun 13, 2026</span>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <small class="text-muted d-block">Student Name</small>
                <span class="fw-semibold">John Doe</span>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block">Class</small>
                <span class="fw-semibold">Grade 10A</span>
            </div>
            <div class="col-md-3">
                <small class="text-muted d-block">Admission No.</small>
                <span class="fw-semibold">ADM-2025001</span>
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Fee Head</th>
                    <th>Period</th>
                    <th class="text-end">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>1</td><td>Tuition Fee</td><td>June 2026</td><td class="text-end">$2,500.00</td></tr>
                <tr><td>2</td><td>Transport Fee</td><td>June 2026</td><td class="text-end">$50.00</td></tr>
                <tr><td>3</td><td>Lab Fee</td><td>June 2026</td><td class="text-end">$30.00</td></tr>
                <tr class="table-warning"><td></td><td colspan="2"><strong>Late Fee</strong></td><td class="text-end">$25.00</td></tr>
            </tbody>
            <tfoot>
                <tr class="fw-bold">
                    <td colspan="3" class="text-end">Total</td>
                    <td class="text-end text-primary fs-5">$2,605.00</td>
                </tr>
            </tfoot>
        </table>

        <div class="row mt-4">
            <div class="col-md-6">
                <small class="text-muted d-block">Payment Method</small>
                <span class="fw-semibold">Credit Card</span>
            </div>
            <div class="col-md-6 text-end">
                <small class="text-muted d-block">Transaction ID</small>
                <span class="fw-semibold">TXN-{{ strtoupper(substr(md5(rand()),0,10)) }}</span>
            </div>
        </div>

        <hr>
        <div class="d-flex justify-content-between mt-4">
            <div>
                <small class="text-muted d-block">Received By</small>
                <span class="fw-semibold">Accounts Department</span>
            </div>
            <div class="text-end">
                <small class="text-muted d-block">Authorized Signature</small>
                <div style="height:40px;"></div>
                <span>_________________</span>
            </div>
        </div>
        <p class="text-muted small text-center mt-4 mb-0">This is a computer-generated receipt.</p>
    </div>
</div>
@endsection