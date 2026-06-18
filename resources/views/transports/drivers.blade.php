@extends('layouts.app')
@section('title', 'Drivers')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-id-card me-2"></i>Drivers</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('transport.index') }}">Transport</a></li><li class="breadcrumb-item active">Drivers</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDriverModal"><i class="fas fa-plus me-1"></i>Add Driver</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Name','License No.','Phone','Experience','Assigned Vehicle','Status','Actions']">
            @foreach(range(1,6) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td class="fw-semibold">{{ ['John Driver','Mike Driver','Steve Driver','Tom Driver','Dave Driver','Sam Driver'][$i-1] }}</td>
                <td>DL-{{ sprintf('%05d',$i+100) }}</td>
                <td>+1-555-{{ sprintf('%04d',$i+8000) }}</td>
                <td>{{ rand(5,20) }} yrs</td>
                <td>Bus {{ 100+$i }}</td>
                <td><span class="badge bg-success">Active</span></td>
                <td><button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
<x-modal id="addDriverModal" title="Add Driver">
    <form>
        <x-form-input name="name" label="Full Name" required />
        <x-form-input name="license_no" label="License Number" />
        <x-form-input name="phone" label="Phone" />
        <x-form-input name="experience" label="Experience (years)" type="number" />
        <x-form-select name="assigned_vehicle" label="Assigned Vehicle" :options="['B101'=>'Bus 101','B102'=>'Bus 102']" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection