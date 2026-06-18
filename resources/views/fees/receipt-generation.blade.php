@extends('layouts.app')
@section('title', 'Receipt Generation')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-receipt me-2"></i>Receipt Generation</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Fees</a></li><li class="breadcrumb-item active">Receipt Generation</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-auto"><input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}"></div>
            <div class="col-auto"><input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}"></div>
            <div class="col-auto"><input type="text" name="receipt_no" class="form-control form-control-sm" placeholder="Receipt No" value="{{ request('receipt_no') }}"></div>
            <div class="col-auto"><button type="submit" class="btn btn-sm btn-outline-primary">Search</button></div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Receipt No','Student','Admission No','Fee Structure','Amount','Paid','Date','Status']">
            @foreach($receipts as $r)
            <tr>
                <td><code class="fw-bold">{{ $r->receipt_number }}</code></td>
                <td>{{ $r->first_name }} {{ $r->last_name }}</td>
                <td>{{ $r->admission_no ?? '-' }}</td>
                <td>{{ $r->structure_name ?? '-' }}</td>
                <td>{{ number_format($r->total_amount, 2) }}</td>
                <td>{{ number_format($r->paid_amount, 2) }}</td>
                <td>{{ $r->payment_date }}</td>
                <td><span class="badge bg-{{ $r->status=='paid'?'success':'warning' }}">{{ ucfirst($r->status) }}</span></td>
            </tr>
            @endforeach
            @if($receipts->isEmpty())<tr><td colspan="8" class="text-center text-muted py-3">No receipts found</td></tr>@endif
        </x-table>
    </div>
    <x-pagination :paginator="$receipts" />
</div>
@endsection
