@extends('layouts.app')
@section('title', 'Overtime')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-clock me-2"></i>Overtime Records</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Payroll</a></li><li class="breadcrumb-item active">Overtime</li></ol></nav>
    </div>
    <a href="#" class="btn btn-primary btn-sm">+ Add Overtime</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Employee','Date','Hours','Rate','Amount','Status']">
            @forelse($records as $r)
            <tr>
                <td class="fw-semibold">{{ $r->first_name }} {{ $r->last_name }}<br><small class="text-muted">{{ $r->employee_no }}</small></td>
                <td>{{ $r->date ? \Carbon\Carbon::parse($r->date)->format('d-m-Y') : ($r->overtime_date ? \Carbon\Carbon::parse($r->overtime_date)->format('d-m-Y') : '-') }}</td>
                <td>{{ $r->hours ?? $r->total_hours }}</td>
                <td>{{ number_format($r->rate ?? $r->rate_multiplier ?? 0, 2) }}</td>
                <td class="fw-bold">{{ number_format($r->amount, 2) }}</td>
                <td>
                    @php $b = match($r->status) { 'approved' => 'success', 'pending' => 'warning', 'rejected' => 'danger', default => 'secondary' }; @endphp
                    <span class="badge bg-{{ $b }}">{{ ucfirst($r->status) }}</span>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted py-3">No overtime records found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$records" />
</div>
@endsection
