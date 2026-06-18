@extends('layouts.app')
@section('title', 'Installment Plans')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-calendar-alt me-2"></i>Installment Plans</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Fees</a></li><li class="breadcrumb-item active">Installment Plans</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-auto">
                <select name="fee_structure_id" class="form-select form-select-sm"><option value="">All Structures</option>
                    @foreach($structures as $s)<option value="{{ $s->id }}" {{ request('fee_structure_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-auto">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                    <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
                </select>
            </div>
            <div class="col-auto"><button type="submit" class="btn btn-sm btn-outline-primary">Filter</button></div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Fee Structure','Installment','Amount','Due Date','Order','Late Fee','Status']">
            @foreach($installments as $i)
            <tr>
                <td>{{ $i->structure_name ?? '-' }}</td>
                <td class="fw-semibold">{{ $i->name }}</td>
                <td>{{ number_format($i->amount, 2) }}</td>
                <td>{{ $i->due_date }}</td>
                <td>{{ $i->order }}</td>
                <td>{{ $i->late_fee_applicable ? number_format($i->late_fee_amount,2) : 'N/A' }}</td>
                <td><span class="badge bg-{{ $i->status=='active'?'success':'secondary' }}">{{ ucfirst($i->status) }}</span></td>
            </tr>
            @endforeach
            @if($installments->isEmpty())<tr><td colspan="7" class="text-center text-muted py-3">No installment plans found</td></tr>@endif
        </x-table>
    </div>
    <x-pagination :paginator="$installments" />
</div>
@endsection
