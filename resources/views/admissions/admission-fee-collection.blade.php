@extends('layouts.app')
@section('title', 'Admission Fee Collection')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-money-bill-wave me-2"></i>Admission Fee Collection</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('admissions.index') }}">Admissions</a></li><li class="breadcrumb-item active">Fee Collection</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#feeModal"><i class="fas fa-plus me-1"></i>Collect Fee</button>
</div>

<div class="row g-2 mb-3">
    <div class="col-md-4"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-success">${{ number_format($totalAdmissionFees,2) }}</h5><small class="text-muted">Total Admission Fees</small></div></div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end mb-2">
            <div class="col-md-3"><select name="payment_method" class="form-select form-select-sm" onchange="this.form.submit()"><option value="">All Methods</option><option value="cash" {{ request('payment_method')=='cash'?'selected':'' }}>Cash</option><option value="card" {{ request('payment_method')=='card'?'selected':'' }}>Card</option><option value="bank_transfer" {{ request('payment_method')=='bank_transfer'?'selected':'' }}>Bank Transfer</option><option value="cheque" {{ request('payment_method')=='cheque'?'selected':'' }}>Cheque</option></select></div>
        </form>
    </div>
    <div class="card-body p-0">
        <x-table :headers="['#','Receipt','Student','Admission No','Amount','Method','Date']">
            @forelse($collections as $c)
            <tr>
                <td>{{ $loop->iteration + ($collections->currentPage()-1)*$collections->perPage() }}</td>
                <td><small class="text-muted">{{ $c->receipt_number ?? '-' }}</small></td>
                <td class="fw-semibold">{{ $c->first_name }} {{ $c->last_name ?? '' }}</td>
                <td>{{ $c->admission_no }}</td>
                <td>${{ number_format($c->paid_amount,2) }}</td>
                <td>{{ ucfirst($c->payment_method) }}</td>
                <td>{{ \Carbon\Carbon::parse($c->payment_date)->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center text-muted py-4">No fee collections found</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$collections" />

<x-modal id="feeModal" title="Collect Admission Fee">
    <form method="POST" action="{{ route('admissions.admission-fee-collection.store') }}">
        @csrf
        <x-form-select name="student_id" label="Student" :options="$students->pluck('first_name','id')->toArray()" required />
        <x-form-select name="fee_structure_id" label="Fee Structure" :options="$feeStructures->pluck('name','id')->toArray()" required />
        <div class="row g-2">
            <div class="col-md-6"><x-form-input name="amount" label="Amount" type="number" step="0.01" required /></div>
            <div class="col-md-6"><x-form-select name="payment_method" label="Payment Method" :options="['cash'=>'Cash','card'=>'Card','bank_transfer'=>'Bank Transfer','cheque'=>'Cheque']" required /></div>
        </div>
        <x-form-input name="payment_date" label="Payment Date" type="date" required />
    </form>
    <x-slot:footer><button class="btn btn-primary" onclick="$('#feeModal form').submit()">Collect</button><button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button></x-slot:footer>
</x-modal>
@endsection
