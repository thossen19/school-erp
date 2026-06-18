@extends('layouts.app')

@section('title', 'Stock Transfers')

@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-exchange-alt me-2"></i>Stock Transfers</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Inventory</li><li class="breadcrumb-item active">Stock Transfers</li></ol></nav>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Date','Item','Type','Quantity','Reference','Notes']">
            @forelse($transfers as $t)
                <tr>
                    <td>{{ $t->movement_date ? date('d M Y', strtotime($t->movement_date)) : '-' }}</td>
                    <td class="fw-semibold">{{ $t->item_name ?? $t->item_code ?? '-' }}</td>
                    <td><span class="badge bg-{{ $t->type === 'in' ? 'success' : 'danger' }}">{{ ucfirst($t->type) }}</span></td>
                    <td>{{ $t->quantity }}</td>
                    <td>{{ $t->reference_type ?? '-' }}</td>
                    <td>{{ $t->remarks ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-3">No stock transfers found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$transfers" />
</div>
@endsection
