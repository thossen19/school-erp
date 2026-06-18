@extends('layouts.app')
@section('title', 'Auto Reminder')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-bell me-2"></i>Auto Reminder</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Fees</a></li><li class="breadcrumb-item active">Auto Reminder</li></ol></nav>
    </div>
</div>
<div class="row g-2 mb-3">
    <div class="col-md-4"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-danger">{{ $overdueCount }}</h5><small class="text-muted">Overdue (Pending)</small></div></div>
    <div class="col-md-4"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-warning">{{ $partialCount }}</h5><small class="text-muted">Partial Payments</small></div></div>
    <div class="col-md-4"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-primary">{{ number_format($totalDue, 2) }}</h5><small class="text-muted">Total Due Amount</small></div></div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-2"><h6 class="fw-semibold mb-0"><i class="fas fa-list me-2"></i>Due Fees Requiring Reminder</h6></div>
    <div class="card-body p-0">
        <x-table :headers="['Student','Admission No','Fee Structure','Total','Paid','Balance','Date','Status']">
            @foreach($dueFees as $d)
            <tr>
                <td>{{ $d->first_name }} {{ $d->last_name }}</td>
                <td>{{ $d->admission_no ?? '-' }}</td>
                <td>{{ $d->structure_name ?? '-' }}</td>
                <td>{{ number_format($d->total_amount, 2) }}</td>
                <td>{{ number_format($d->paid_amount, 2) }}</td>
                <td class="text-danger fw-bold">{{ number_format($d->balance_amount, 2) }}</td>
                <td>{{ $d->payment_date }}</td>
                <td><span class="badge bg-{{ $d->status=='partial'?'warning text-dark':'danger' }}">{{ ucfirst($d->status) }}</span></td>
            </tr>
            @endforeach
            @if($dueFees->isEmpty())<tr><td colspan="8" class="text-center text-success py-3">No reminders needed. All fees collected.</td></tr>@endif
        </x-table>
    </div>
</div>
@endsection
