@extends('layouts.app')
@section('title', 'Journal Entry')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-pen-alt me-2"></i>Journal Entry</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Accounting</li><li class="breadcrumb-item active">Journal Entry</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Date','Entry #','Description','Total Debit (৳)','Total Credit (৳)','Status']">
            @forelse($entries as $e)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($e->date)->format('d-m-Y') }}</td>
                    <td class="font-monospace">{{ $e->entry_number ?? $e->id }}</td>
                    <td>{{ Str::limit($e->description, 50) }}</td>
                    <td class="text-end">{{ number_format($e->total_debit, 2) }}</td>
                    <td class="text-end">{{ number_format($e->total_credit, 2) }}</td>
                    <td><span class="badge bg-{{ $e->status === 'posted' ? 'success' : 'warning' }}">{{ ucfirst($e->status) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-3">No journal entries found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$entries" />
</div>
@endsection
