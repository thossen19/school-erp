@extends('layouts.app')
@section('title', 'Transport Routes')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-route me-2"></i>Transport Routes</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Transport</a></li><li class="breadcrumb-item active">Routes</li></ol></nav>
    </div>
    <a href="{{ route('transport.routes.create') }}" class="btn btn-primary btn-sm">+ Add Route</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Route Name','Number','Start Point','End Point','Distance (km)','Stops','Status']">
            @forelse($routes as $r)
            <tr>
                <td class="fw-semibold">{{ $r->route_name }}</td>
                <td>{{ $r->route_number ?? '-' }}</td>
                <td>{{ $r->start_point ?? '-' }}</td>
                <td>{{ $r->end_point ?? '-' }}</td>
                <td>{{ $r->distance_km ?? '-' }}</td>
                <td>{{ $r->total_stops ?? $r->stops ?? 0 }}</td>
                <td><span class="badge bg-{{ $r->status ? 'success' : 'danger' }}">{{ $r->status ? 'Active' : 'Inactive' }}</span></td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center text-muted py-3">No routes found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$routes" />
</div>
@endsection
