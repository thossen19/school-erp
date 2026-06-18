@extends('layouts.app')

@section('title', 'Stock Audit')

@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-clipboard-check me-2"></i>Stock Audit</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Inventory</li><li class="breadcrumb-item active">Stock Audit</li></ol></nav>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Date','Item','Expected','Actual','Difference','Conducted By','Remarks']">
            @forelse($audits as $a)
                <tr>
                    <td>{{ $a->audit_date ? date('d M Y', strtotime($a->audit_date)) : '-' }}</td>
                    <td class="fw-semibold">{{ $a->item_name ?? $a->item_code ?? '-' }}</td>
                    <td>{{ $a->expected_quantity }}</td>
                    <td>{{ $a->actual_quantity }}</td>
                    <td>
                        @php $diff = $a->difference; @endphp
                        <span class="badge bg-{{ $diff == 0 ? 'success' : ($diff > 0 ? 'warning' : 'danger') }}">
                            {{ $diff > 0 ? '+' : '' }}{{ $diff }}
                        </span>
                    </td>
                    <td>{{ $a->conducted_by ?? '-' }}</td>
                    <td>{{ $a->remarks ?? $a->reason ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-3">No audit records found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$audits" />
</div>
@endsection
