@extends('layouts.app')
@section('title', 'Discount Management')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-percentage me-2"></i>Discount Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Fees</a></li><li class="breadcrumb-item active">Discount Management</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-auto">
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    <option value="percentage" {{ request('type')=='percentage'?'selected':'' }}>Percentage</option>
                    <option value="fixed" {{ request('type')=='fixed'?'selected':'' }}>Fixed</option>
                </select>
            </div>
            <div class="col-auto">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="1" {{ request('status')==='1'?'selected':'' }}>Active</option>
                    <option value="0" {{ request('status')==='0'?'selected':'' }}>Inactive</option>
                </select>
            </div>
            <div class="col-auto"><button type="submit" class="btn btn-sm btn-outline-primary">Filter</button></div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Name','Code','Type','Value','Applicable To','Valid Period','Status']">
            @foreach($discounts as $d)
            <tr>
                <td class="fw-semibold">{{ $d->name }}</td>
                <td><code>{{ $d->code ?? '-' }}</code></td>
                <td><span class="badge bg-{{ $d->type=='percentage'?'info':'primary' }}">{{ ucfirst($d->type) }}</span></td>
                <td>{{ $d->type=='percentage' ? $d->value.'%' : number_format($d->value,2) }}</td>
                <td>{{ ucfirst($d->applicable_to ?? 'All') }}</td>
                <td>{{ $d->valid_from ? $d->valid_from.' to '.($d->valid_until??'∞') : 'Unlimited' }}</td>
                <td><span class="badge bg-{{ $d->status?'success':'danger' }}">{{ $d->status?'Active':'Inactive' }}</span></td>
            </tr>
            @endforeach
            @if($discounts->isEmpty())<tr><td colspan="7" class="text-center text-muted py-3">No discounts found</td></tr>@endif
        </x-table>
    </div>
    <x-pagination :paginator="$discounts" />
</div>
@endsection
