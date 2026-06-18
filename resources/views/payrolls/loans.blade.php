@extends('layouts.app')
@section('title', 'Loans')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-hand-holding-usd me-2"></i>Loans</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('payroll.index') }}">Payroll</a></li><li class="breadcrumb-item active">Loans</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLoanModal"><i class="fas fa-plus me-1"></i>New Loan</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Employee','Loan Type','Amount','Interest','Duration','Monthly EMI','Status','Actions']">
            @foreach(range(1,5) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td><a href="#" class="text-decoration-none">Employee {{ $i }}</a></td>
                <td>{{ ['Personal Loan','Home Loan','Education Loan','Vehicle Loan','Personal Loan'][$i-1] }}</td>
                <td>${{ number_format(rand(1000,10000),2) }}</td>
                <td>{{ rand(5,12) }}%</td>
                <td>{{ rand(6,24) }} months</td>
                <td>${{ number_format(rand(100,500),2) }}</td>
                <td><span class="badge bg-{{ ['success','warning','info'][$i%3] }}">{{ ['Active','Pending','Completed'][$i%3] }}</span></td>
                <td><button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
<x-modal id="addLoanModal" title="New Loan">
    <form>
        <x-form-select name="employee" label="Employee" :options="['1'=>'John Smith']" />
        <x-form-select name="loan_type" label="Loan Type" :options="['personal'=>'Personal','home'=>'Home','education'=>'Education','vehicle'=>'Vehicle']" />
        <x-form-input name="amount" label="Loan Amount ($)" type="number" step="0.01" />
        <x-form-input name="interest_rate" label="Interest Rate (%)" type="number" step="0.1" />
        <x-form-input name="duration_months" label="Duration (months)" type="number" />
        <x-form-textarea name="purpose" label="Purpose" rows="2" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Approve Loan</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection