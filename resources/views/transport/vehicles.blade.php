@extends('layouts.app')
@section('title', 'Transport Vehicles')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-bus me-2"></i>Transport Vehicles</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Transport</a></li><li class="breadcrumb-item active">Vehicles</li></ol></nav>
    </div>
    <a href="{{ route('transport.vehicles.create') }}" class="btn btn-primary btn-sm">+ Add Vehicle</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Vehicle No','Type','Model','Capacity','Manufacturer','Status']">
            @forelse($vehicles as $v)
            <tr>
                <td class="fw-semibold">{{ $v->vehicle_number }}</td>
                <td>{{ ucfirst($v->vehicle_type ?? '-') }}</td>
                <td>{{ $v->vehicle_model ?? '-' }}</td>
                <td>{{ $v->capacity }}</td>
                <td>{{ $v->manufacturer ?? '-' }}</td>
                <td>
                    @php $b = match($v->status) { 'active' => 'success', 'maintenance' => 'warning', 'retired' => 'danger', default => 'secondary' }; @endphp
                    <span class="badge bg-{{ $b }}">{{ ucfirst($v->status) }}</span>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted py-3">No vehicles found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$vehicles" />
</div>
@endsection
