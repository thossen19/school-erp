@extends('layouts.app')
@section('title', 'Transport Allocations')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-user-check me-2"></i>Transport Allocations</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('transport.index') }}">Transport</a></li><li class="breadcrumb-item active">Allocations</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#allocModal"><i class="fas fa-plus me-1"></i>Allocate Route</button>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Student','Route','Vehicle','Pickup Point','Drop Point','Fee','Status','Actions']">
            @foreach(range(1,8) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td><a href="#" class="text-decoration-none">Student {{ $i }}</a></td>
                <td>{{ ['Route A','Route B','Route C','Route A','Route B','Route C','Route D','Route A'][$i-1] }}</td>
                <td>Bus {{ 100+$i }}</td>
                <td>Stop {{ $i }}</td>
                <td>School</td>
                <td>${{ rand(30,60) }}</td>
                <td><span class="badge bg-success">Active</span></td>
                <td><button class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i></button></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
<x-modal id="allocModal" title="Allocate Route to Student">
    <form>
        <x-form-select name="student" label="Student" :options="['1'=>'John Doe']" />
        <x-form-select name="route" label="Route" :options="['1'=>'Route A - North']" />
        <x-form-input name="pickup_point" label="Pickup Point" />
        <x-form-input name="drop_point" label="Drop Point" value="School" />
        <x-form-input name="fee" label="Monthly Fee ($)" type="number" />
    </form>
    <x-slot:footer><button class="btn btn-primary">Allocate</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection