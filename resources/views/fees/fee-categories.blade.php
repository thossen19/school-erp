@extends('layouts.app')
@section('title', 'Fee Categories')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-tags me-2"></i>Fee Categories</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Fees</a></li><li class="breadcrumb-item active">Fee Categories</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2">
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
        <x-table :headers="['Name','Code','Description','Optional','Frequency','Status']">
            @foreach($categories as $c)
            <tr>
                <td class="fw-semibold">{{ $c->name }}</td>
                <td><code>{{ $c->code }}</code></td>
                <td>{{ Str::limit($c->description, 40) ?? '-' }}</td>
                <td><span class="badge bg-{{ $c->is_optional?'warning text-dark':'secondary' }}">{{ $c->is_optional?'Optional':'Required' }}</span></td>
                <td>{{ ucfirst($c->frequency ?? '-') }}</td>
                <td><span class="badge bg-{{ $c->status?'success':'danger' }}">{{ $c->status?'Active':'Inactive' }}</span></td>
            </tr>
            @endforeach
            @if($categories->isEmpty())<tr><td colspan="6" class="text-center text-muted py-3">No categories found</td></tr>@endif
        </x-table>
    </div>
    <x-pagination :paginator="$categories" />
</div>
@endsection
