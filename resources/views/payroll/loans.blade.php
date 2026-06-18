@extends('layouts.app')
@section('title', 'Loans')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-hand-holding-usd me-2"></i>Loan Requests</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Payroll</a></li><li class="breadcrumb-item active">Loans</li></ol></nav>
    </div>
    <a href="#" class="btn btn-primary btn-sm">+ New Loan Request</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Employee','Amount','Installments','Purpose','Status']">
            @forelse($loans as $l)
            <tr>
                <td class="fw-semibold">{{ $l->first_name }} {{ $l->last_name }}<br><small class="text-muted">{{ $l->employee_no }}</small></td>
                <td>{{ number_format($l->loan_amount ?? $l->amount, 2) }}</td>
                <td>{{ $l->paid_installments ?? 0 }} / {{ $l->total_installments ?? $l->installment_count }}</td>
                <td>{{ \Illuminate\Support\Str::limit($l->purpose, 40) }}</td>
                <td>
                    @php $b = match($l->status) { 'approved' => 'success', 'pending' => 'warning', 'rejected' => 'danger', 'paid' => 'info', default => 'secondary' }; @endphp
                    <span class="badge bg-{{ $b }}">{{ ucfirst($l->status) }}</span>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted py-3">No loan requests found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$loans" />
</div>
@endsection
