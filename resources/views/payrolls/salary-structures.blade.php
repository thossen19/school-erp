@extends('layouts.app')
@section('title', 'Salary Structures')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-cog me-2"></i>Salary Structures</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('payroll.index') }}">Payroll</a></li><li class="breadcrumb-item active">Salary Structures</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStructModal"><i class="fas fa-plus me-1"></i>Add Structure</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Structure Name','Department','Designation','Basic Min','Basic Max','Status','Actions']">
            @foreach(range(1,6) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td class="fw-semibold">{{ ['Teaching Grade I','Teaching Grade II','Admin Grade I','Admin Grade II','Accountant','Support Staff'][$i-1] }}</td>
                <td>{{ ['Teaching','Teaching','Admin','Admin','Accounts','Admin'][$i-1] }}</td>
                <td>{{ ['Senior Teacher','Teacher','Manager','Assistant','Accountant','Staff'][$i-1] }}</td>
                <td>${{ number_format(rand(3000,5000),2) }}</td>
                <td>${{ number_format(rand(5000,8000),2) }}</td>
                <td><span class="badge bg-success">Active</span></td>
                <td><button class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
<x-modal id="addStructModal" title="Add Salary Structure">
    <form>
        <x-form-input name="name" label="Structure Name" required />
        <x-form-select name="department" label="Department" :options="['teaching'=>'Teaching']" />
        <x-form-select name="designation" label="Designation" :options="['teacher'=>'Teacher']" />
        <x-form-input name="basic_min" label="Basic Min ($)" type="number" />
        <x-form-input name="basic_max" label="Basic Max ($)" type="number" />
        <x-form-input name="allowances" label="Allowances (%)" type="number" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection