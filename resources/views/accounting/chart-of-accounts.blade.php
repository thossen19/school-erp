@extends('layouts.app')
@section('title', 'Chart of Accounts')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-list me-2"></i>Chart of Accounts</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Accounting</li><li class="breadcrumb-item active">Chart of Accounts</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Code','Account Name','Type','Sub Type','Balance','Status']">
            @forelse($accounts as $a)
                <tr>
                    <td class="fw-semibold font-monospace">{{ $a->code }}</td>
                    <td>{{ $a->name }}</td>
                    <td>{{ ucfirst($a->type) }}</td>
                    <td>{{ $a->sub_type ?? '-' }}</td>
                    <td class="fw-bold">৳{{ number_format($a->balance ?? 0, 2) }}</td>
                    <td><span class="badge bg-{{ $a->is_active ? 'success' : 'danger' }}">{{ $a->is_active ? 'Active' : 'Inactive' }}</span></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-3">No accounts found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$accounts" />
</div>
@endsection
