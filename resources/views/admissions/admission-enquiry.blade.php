@extends('layouts.app')
@section('title', 'Admission Enquiries')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-question-circle me-2"></i>Admission Enquiries</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('admissions.index') }}">Admissions</a></li><li class="breadcrumb-item active">Enquiries</li></ol></nav>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end mb-2">
            <div class="col-md-3"><select name="status" class="form-select form-select-sm" onchange="this.form.submit()"><option value="">All Status</option><option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option><option value="contacted" {{ request('status')=='contacted'?'selected':'' }}>Contacted</option><option value="converted" {{ request('status')=='converted'?'selected':'' }}>Converted</option><option value="closed" {{ request('status')=='closed'?'selected':'' }}>Closed</option></select></div>
        </form>
    </div>
    <div class="card-body p-0">
        <x-table :headers="['#','Name','Phone','Email','Source','Status','Follow Up','Created']">
            @forelse($enquiries as $e)
            <tr>
                <td>{{ $loop->iteration + ($enquiries->currentPage()-1)*$enquiries->perPage() }}</td>
                <td class="fw-semibold">{{ $e->name }}</td>
                <td>{{ $e->phone }}</td>
                <td>{{ $e->email ?? '-' }}</td>
                <td>{{ $e->source ?? '-' }}</td>
                <td><span class="badge bg-{{ $e->status=='converted'?'success':($e->status=='contacted'?'warning':'primary') }}">{{ ucfirst($e->status) }}</span></td>
                <td>{{ $e->follow_up_date ? \Carbon\Carbon::parse($e->follow_up_date)->format('M d, Y') : '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($e->created_at)->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted py-4">No enquiries found</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$enquiries" />
@endsection
