@extends('layouts.app')
@section('title', 'Fee Collections')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-hand-holding-usd me-2"></i>Fee Collections</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('fees.index') }}">Fees</a></li><li class="breadcrumb-item active">Collections</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#collectModal"><i class="fas fa-plus me-1"></i>Record Payment</button>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><x-stats-card title="Total Collected" value="$284,500" icon="fa-money-bill" color="success" /></div>
    <div class="col-md-3"><x-stats-card title="Pending" value="$45,200" icon="fa-clock" color="warning" /></div>
    <div class="col-md-3"><x-stats-card title="Overdue" value="$12,800" icon="fa-exclamation-circle" color="danger" /></div>
    <div class="col-md-3"><x-stats-card title="Collection Rate" value="86%" icon="fa-percentage" color="info" /></div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Receipt No.','Student','Fee Head','Amount','Paid Date','Method','Status','Actions']">
            @foreach(range(1,10) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td><span class="fw-semibold">RCT-{{ sprintf('%05d',$i) }}</span></td>
                <td><a href="#" class="text-decoration-none">Student {{ $i }}</a></td>
                <td>{{ ['Tuition','Transport','Lab','Activity'][$i%4] }}</td>
                <td>${{ number_format(rand(100,2500),2) }}</td>
                <td>Jun {{ $i }}, 2026</td>
                <td>{{ ['Cash','Card','Bank Transfer','Cheque'][$i%4] }}</td>
                <td><span class="badge bg-success">Paid</span></td>
                <td><button class="btn btn-sm btn-outline-primary"><i class="fas fa-receipt"></i> Receipt</button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>

<x-modal id="collectModal" title="Record Fee Payment">
    <form>
        <x-form-select name="student" label="Student" :options="['1'=>'John Doe']" required />
        <x-form-select name="fee_head" label="Fee Head" :options="['tuition'=>'Tuition Fee','transport'=>'Transport Fee','lab'=>'Lab Fee']" />
        <x-form-input name="amount" label="Amount ($)" type="number" step="0.01" required />
        <x-form-input name="paid_date" label="Paid Date" type="date" value="{{ date('Y-m-d') }}" />
        <x-form-select name="payment_method" label="Payment Method" :options="['cash'=>'Cash','card'=>'Credit/Debit Card','bank'=>'Bank Transfer','cheque'=>'Cheque']" />
        <x-form-input name="reference_no" label="Reference No." placeholder="Transaction ID" />
        <x-form-textarea name="notes" label="Notes" rows="2" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Record Payment</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection