@extends('layouts.app')
@section('title', 'Process Payroll')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-calculator me-2"></i>Process Payroll</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('payroll.index') }}">Payroll</a></li><li class="breadcrumb-item active">Process</li></ol></nav>
    </div>
</div>
<form>
    @csrf
    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent border-bottom py-3"><h6 class="fw-semibold mb-0"><i class="fas fa-cog me-2 text-primary"></i>Payroll Settings</h6></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4"><x-form-select name="month" label="Payroll Month" required :options="['2026-06'=>'June 2026','2026-05'=>'May 2026']" /></div>
                <div class="col-md-4"><x-form-select name="department" label="Department" :options="['all'=>'All Departments','teaching'=>'Teaching','admin'=>'Administration']" /></div>
                <div class="col-md-4"><x-form-select name="payment_method" label="Payment Method" :options="['bank'=>'Bank Transfer','cash'=>'Cash','cheque'=>'Cheque']" /></div>
                <div class="col-md-4"><x-form-input name="process_date" label="Process Date" type="date" value="{{ date('Y-m-d') }}" /></div>
                <div class="col-md-4"><x-form-input name="payment_date" label="Payment Date" type="date" /></div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-play me-1"></i>Process Payroll</button>
        <a href="{{ route('payroll.index') }}" class="btn btn-outline-danger"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection