@extends('layouts.app')
@section('title', 'Vehicles')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-truck me-2"></i>Vehicles</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('transport.index') }}">Transport</a></li><li class="breadcrumb-item active">Vehicles</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVehicleModal"><i class="fas fa-plus me-1"></i>Add Vehicle</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Vehicle No.','Type','Capacity','Model','Year','Insurance','Status','Actions']">
            @foreach(range(1,6) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td class="fw-semibold">Bus {{ 100+$i }}</td>
                <td>{{ ['Minibus','Coach','Minibus','Coach','Mini Van','Coach'][$i-1] }}</td>
                <td>{{ [25,45,25,55,15,50][$i-1] }}</td>
                <td>{{ ['Toyota Hiace','Volvo 9400','Toyota Hiace','Mercedes Benz','Ford Transit','Volvo 9400'][$i-1] }}</td>
                <td>20{{ sprintf('%02d',rand(18,24)) }}</td>
                <td>Dec 2026</td>
                <td><span class="badge bg-{{ ['success','warning','success','success','danger','success'][$i-1] }}">{{ ['Active','Maintenance','Active','Active','Out of Service','Active'][$i-1] }}</span></td>
                <td><button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
<x-modal id="addVehicleModal" title="Add Vehicle">
    <form>
        <x-form-input name="vehicle_no" label="Vehicle No." required />
        <x-form-select name="type" label="Type" :options="['minibus'=>'Minibus','coach'=>'Coach','van'=>'Van']" />
        <x-form-input name="capacity" label="Capacity" type="number" />
        <x-form-input name="model" label="Model" />
        <x-form-input name="year" label="Year" type="number" />
        <x-form-input name="insurance_expiry" label="Insurance Expiry" type="date" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Save</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection