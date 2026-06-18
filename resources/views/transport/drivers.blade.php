@extends('layouts.app')
@section('title', 'Transport Drivers')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-id-card me-2"></i>Transport Drivers</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Transport</a></li><li class="breadcrumb-item active">Drivers</li></ol></nav>
    </div>
    <a href="{{ route('transport.drivers.create') }}" class="btn btn-primary btn-sm">+ Add Driver</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Name','Phone','Email','License No.','License Expiry','Status']">
            @forelse($drivers as $d)
            <tr>
                <td class="fw-semibold">{{ $d->first_name }} {{ $d->last_name ?? '' }}</td>
                <td>{{ $d->phone }}</td>
                <td>{{ $d->email ?? '-' }}</td>
                <td>{{ $d->license_number ?? '-' }}</td>
                <td>{{ $d->license_expiry ? \Carbon\Carbon::parse($d->license_expiry)->format('d-m-Y') : '-' }}</td>
                <td>
                    @php $b = match($d->status) { 'active' => 'success', 'inactive' => 'secondary', 'suspended' => 'danger', default => 'secondary' }; @endphp
                    <span class="badge bg-{{ $b }}">{{ ucfirst($d->status) }}</span>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted py-3">No drivers found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$drivers" />
</div>
@endsection
