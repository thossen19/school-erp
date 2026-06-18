@extends('layouts.app')
@section('title', 'Overtime')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-clock me-2"></i>Overtime</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('payroll.index') }}">Payroll</a></li><li class="breadcrumb-item active">Overtime</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOTModal"><i class="fas fa-plus me-1"></i>Add Overtime</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Employee','Date','Hours Worked','Rate/Hour','Total Amount','Approved By','Status','Actions']">
            @foreach(range(1,6) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td><a href="#" class="text-decoration-none">Employee {{ $i }}</a></td>
                <td>Jun {{ $i }}, 2026</td>
                <td>{{ rand(2,8) }} hrs</td>
                <td>${{ rand(20,50) }}</td>
                <td class="fw-bold">${{ rand(40,400) }}</td>
                <td>Manager</td>
                <td><span class="badge bg-{{ ['success','warning'][$i%2] }}">{{ ['Approved','Pending'][$i%2] }}</span></td>
                <td><button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
<x-modal id="addOTModal" title="Add Overtime">
    <form>
        <x-form-select name="employee" label="Employee" :options="['1'=>'John Smith']" />
        <x-form-input name="date" label="Date" type="date" />
        <x-form-input name="hours" label="Hours Worked" type="number" step="0.5" />
        <x-form-input name="rate" label="Rate per Hour ($)" type="number" step="0.01" />
        <x-form-textarea name="reason" label="Reason" rows="2" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection