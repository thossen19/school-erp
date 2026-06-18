@extends('layouts.app')
@section('title', 'Fine Management')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-exclamation-triangle me-2"></i>Fine Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Fees</a></li><li class="breadcrumb-item active">Fine Management</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-auto"><input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}"></div>
            <div class="col-auto"><input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}"></div>
            <div class="col-auto"><button type="submit" class="btn btn-sm btn-outline-primary">Filter</button></div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Student','Admission No','Fee Structure','Fine Amount','Payment Date','Status']">
            @foreach($fines as $f)
            <tr>
                <td>{{ $f->first_name }} {{ $f->last_name }}</td>
                <td>{{ $f->admission_no ?? '-' }}</td>
                <td>{{ $f->structure_name ?? '-' }}</td>
                <td class="text-danger fw-bold">{{ number_format($f->fine_amount, 2) }}</td>
                <td>{{ $f->payment_date }}</td>
                <td><span class="badge bg-{{ $f->status=='paid'?'success':'warning' }}">{{ ucfirst($f->status) }}</span></td>
            </tr>
            @endforeach
            @if($fines->isEmpty())<tr><td colspan="6" class="text-center text-muted py-3">No fines recorded</td></tr>@endif
        </x-table>
    </div>
    <x-pagination :paginator="$fines" />
</div>
@endsection
