@extends('layouts.app')
@section('title', 'Designations')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-tags me-2"></i>Designations</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('hr.index') }}">HR</a></li><li class="breadcrumb-item active">Designations</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDesigModal"><i class="fas fa-plus me-1"></i>Add Designation</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Designation','Department','Grade','Employees','Status','Actions']">
            @foreach(range(1,8) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td class="fw-semibold">{{ ['Principal','Vice Principal','Senior Teacher','Teacher','Accountant','Librarian','IT Admin','Coach'][$i-1] }}</td>
                <td>{{ ['Administration','Administration','Teaching','Teaching','Accounts','Library','IT','Sports'][$i-1] }}</td>
                <td>{{ ['I','II','III','IV','III','III','II','III'][$i-1] }}</td>
                <td>{{ [1,2,15,40,3,2,2,4][$i-1] }}</td>
                <td><span class="badge bg-success">Active</span></td>
                <td><button class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
<x-modal id="addDesigModal" title="Add Designation">
    <form>
        <x-form-input name="name" label="Designation Name" required />
        <x-form-select name="department" label="Department" :options="['teaching'=>'Teaching','admin'=>'Administration']" />
        <x-form-input name="grade" label="Grade" placeholder="e.g. I, II, III" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection