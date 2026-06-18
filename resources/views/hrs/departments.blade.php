@extends('layouts.app')
@section('title', 'Departments')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-building me-2"></i>Departments</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('hr.index') }}">HR</a></li><li class="breadcrumb-item active">Departments</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDeptModal"><i class="fas fa-plus me-1"></i>Add Department</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Department Name','Head','Employees','Description','Status','Actions']">
            @foreach(range(1,8) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td class="fw-semibold">{{ ['Teaching','Administration','Accounts','Library','Sports','IT','Science Lab','Arts & Culture'][$i-1] }}</td>
                <td>{{ ['Dr. Principal','Mr. Adams','Ms. Carter','Mr. Brown','Coach Wilson','Mr. Lee','Dr. Green','Ms. Taylor'][$i-1] }}</td>
                <td>{{ rand(5,40) }}</td>
                <td><small class="text-muted">{{ ['Academic department','Management','Finance','Library services','Sports & PE','Technology','Science','Arts'][$i-1] }}</small></td>
                <td><span class="badge bg-success">Active</span></td>
                <td><button class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
<x-modal id="addDeptModal" title="Add Department">
    <form>
        <x-form-input name="name" label="Department Name" required />
        <x-form-select name="head" label="Department Head" :options="['1'=>'Dr. Principal','2'=>'Mr. Adams']" />
        <x-form-textarea name="description" label="Description" rows="2" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection