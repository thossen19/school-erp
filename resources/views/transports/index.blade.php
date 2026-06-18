@extends('layouts.app')
@section('title', 'Transport')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-bus me-2"></i>Transport Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Transport</li></ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('transport.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Add Route</a>
        <a href="{{ route('transport.vehicles') }}" class="btn btn-outline-info"><i class="fas fa-truck me-1"></i>Vehicles</a>
        <a href="{{ route('transport.drivers') }}" class="btn btn-outline-warning"><i class="fas fa-id-card me-1"></i>Drivers</a>
        <a href="{{ route('transport.allocations') }}" class="btn btn-outline-success"><i class="fas fa-user-check me-1"></i>Allocations</a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Route Name','Vehicle','Driver','Stops','Students','Status','Actions']">
            @foreach(range(1,6) as $i)
            <tr>
                <td>{{ $i }}</td>
                <td><a href="{{ route('transport.show',$i) }}" class="text-decoration-none fw-semibold">{{ ['Route A - North','Route B - South','Route C - East','Route D - West','Route E - Central','Route F - Suburb'][$i-1] }}</a></td>
                <td>Bus {{ sprintf('%03d',$i+10) }}</td>
                <td>{{ ['John Driver','Mike Driver','Steve Driver','Tom Driver','Dave Driver','Sam Driver'][$i-1] }}</td>
                <td>{{ rand(5,12) }}</td>
                <td>{{ rand(15,45) }}</td>
                <td><span class="badge bg-{{ ['success','warning','info'][$i%3] }}">{{ ['Active','Maintenance','Inactive'][$i%3] }}</span></td>
                <td><div class="table-actions"><a href="{{ route('transport.show',$i) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a><a href="{{ route('transport.edit',$i) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a></div></td>
            </tr>
            @endforeach
        </x-table>
    </div>
</div>
@endsection