@extends('layouts.app')
@section('title', 'Fee Structure')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-file-invoice me-2"></i>Fee Structure</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Fees</a></li><li class="breadcrumb-item active">Fee Structure</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-auto">
                <select name="class_id" class="form-select form-select-sm"><option value="">All Classes</option>
                    @foreach($classes as $c)<option value="{{ $c->id }}" {{ request('class_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-auto">
                <select name="fee_category_id" class="form-select form-select-sm"><option value="">All Categories</option>
                    @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ request('fee_category_id')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-auto"><button type="submit" class="btn btn-sm btn-outline-primary">Filter</button></div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Name','Category','Class','Amount','Due Date','Mandatory','Installment','Status']">
            @foreach($structures as $s)
            <tr>
                <td class="fw-semibold">{{ $s->name }}</td>
                <td>{{ $s->category_name ?? '-' }}</td>
                <td>{{ $s->class_name ?? 'All' }}</td>
                <td>{{ number_format($s->amount, 2) }}</td>
                <td>{{ $s->due_date ?? '-' }}</td>
                <td><span class="badge bg-{{ $s->is_mandatory?'primary':'secondary' }}">{{ $s->is_mandatory?'Yes':'No' }}</span></td>
                <td>{{ $s->is_installment ? $s->installment_count.'×' : 'No' }}</td>
                <td><span class="badge bg-{{ $s->status?'success':'danger' }}">{{ $s->status?'Active':'Inactive' }}</span></td>
            </tr>
            @endforeach
            @if($structures->isEmpty())<tr><td colspan="8" class="text-center text-muted py-3">No fee structures found</td></tr>@endif
        </x-table>
    </div>
    <x-pagination :paginator="$structures" />
</div>
@endsection
