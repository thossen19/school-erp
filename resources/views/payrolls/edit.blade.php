@extends('layouts.app')
@section('title', 'Edit Payroll')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-edit me-2"></i>Edit Payroll</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('payroll.index') }}">Payroll</a></li><li class="breadcrumb-item active">Edit</li></ol></nav>
    </div>
</div>
<form>
    @csrf @method('PUT')
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4"><x-form-select name="employee" label="Employee" :options="['1'=>'John Smith']" value="1" /></div>
                <div class="col-md-4"><x-form-input name="basic_salary" label="Basic Salary ($)" type="number" value="5000" /></div>
                <div class="col-md-4"><x-form-input name="allowances" label="Total Allowances ($)" type="number" value="2800" /></div>
                <div class="col-md-4"><x-form-input name="deductions" label="Total Deductions ($)" type="number" value="1650" /></div>
                <div class="col-md-4"><x-form-input name="net_pay" label="Net Pay ($)" type="number" value="6150" /></div>
                <div class="col-md-4"><x-form-select name="status" label="Status" :options="['paid'=>'Paid','pending'=>'Pending']" value="paid" /></div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update</button>
        <a href="{{ route('payroll.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times me-1"></i>Cancel</a>
    </div>
</form>
@endsection