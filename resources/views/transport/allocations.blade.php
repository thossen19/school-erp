@extends('layouts.app')
@section('title', 'Transport Allocations')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-users me-2"></i>Transport Allocations</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Transport</a></li><li class="breadcrumb-item active">Allocations</li></ol></nav>
    </div>
    <a href="#" class="btn btn-primary btn-sm">+ New Allocation</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Route','Pickup','Drop','Status']">
            @forelse($allocations as $a)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $a->transport_route_id }}</td>
                <td>{{ $a->pickup_point ?? $a->transport_route_stop_id ?? '-' }}</td>
                <td>{{ $a->drop_point ?? '-' }}</td>
                <td><span class="badge bg-{{ $a->status == 'active' ? 'success' : 'danger' }}">{{ ucfirst($a->status ?? 'active') }}</span></td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted py-3">No allocations found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$allocations" />
</div>
@endsection
