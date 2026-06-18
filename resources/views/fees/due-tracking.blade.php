@extends('layouts.app')
@section('title', 'Due Tracking')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-clock me-2"></i>Due Tracking</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Fees</a></li><li class="breadcrumb-item active">Due Tracking</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-auto">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Due</option>
                    <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                    <option value="partial" {{ request('status')=='partial'?'selected':'' }}>Partial</option>
                </select>
            </div>
            <div class="col-auto"><button type="submit" class="btn btn-sm btn-outline-primary">Filter</button></div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Student','Admission No','Fee Structure','Total','Paid','Balance','Date','Status']">
            @foreach($dues as $d)
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
            @if($dues->isEmpty())<tr><td colspan="8" class="text-center text-success py-3">No dues! All fees collected.</td></tr>@endif
        </x-table>
    </div>
    <x-pagination :paginator="$dues" />
</div>
@endsection
