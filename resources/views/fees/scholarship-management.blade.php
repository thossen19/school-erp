@extends('layouts.app')
@section('title', 'Scholarship Management')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-award me-2"></i>Scholarship Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="#">Fees</a></li><li class="breadcrumb-item active">Scholarship Management</li></ol></nav>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-auto">
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    <option value="merit" {{ request('type')=='merit'?'selected':'' }}>Merit</option>
                    <option value="need" {{ request('type')=='need'?'selected':'' }}>Need Based</option>
                    <option value="sports" {{ request('type')=='sports'?'selected':'' }}>Sports</option>
                    <option value="other" {{ request('type')=='other'?'selected':'' }}>Other</option>
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
        <x-table :headers="['Name','Type','Amount','Total Slots','Status']">
            @foreach($scholarships as $s)
            <tr>
                <td class="fw-semibold">{{ $s->name }}</td>
                <td><span class="badge bg-info">{{ ucfirst($s->type) }}</span></td>
                <td>{{ number_format($s->amount, 2) }}</td>
                <td>{{ $s->total_slots ?? 'Unlimited' }}</td>
                <td><span class="badge bg-{{ $s->status?'success':'danger' }}">{{ $s->status?'Active':'Inactive' }}</span></td>
            </tr>
            @endforeach
            @if($scholarships->isEmpty())<tr><td colspan="5" class="text-center text-muted py-3">No scholarships found</td></tr>@endif
        </x-table>
    </div>
    <x-pagination :paginator="$scholarships" />
</div>
@endsection
