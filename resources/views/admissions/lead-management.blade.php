@extends('layouts.app')
@section('title', 'Lead Management')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-filter me-2"></i>Lead Management</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('admissions.index') }}">Admissions</a></li><li class="breadcrumb-item active">Lead Management</li></ol></nav>
    </div>
</div>

<div class="row g-2 mb-3">
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-primary">{{ $leads->total() }}</h5><small class="text-muted">Total Leads</small></div></div>
    <div class="col-md-3"><div class="card shadow-sm border-0 text-center p-3"><h5 class="fw-bold text-success">{{ $conversionRate }}%</h5><small class="text-muted">Conversion Rate</small></div></div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end mb-2">
            <div class="col-md-2"><select name="status" class="form-select form-select-sm" onchange="this.form.submit()"><option value="">All Status</option><option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option><option value="contacted" {{ request('status')=='contacted'?'selected':'' }}>Contacted</option><option value="converted" {{ request('status')=='converted'?'selected':'' }}>Converted</option><option value="closed" {{ request('status')=='closed'?'selected':'' }}>Closed</option></select></div>
            <div class="col-md-2"><select name="source" class="form-select form-select-sm" onchange="this.form.submit()"><option value="">All Sources</option><option value="walk-in" {{ request('source')=='walk-in'?'selected':'' }}>Walk-in</option><option value="phone" {{ request('source')=='phone'?'selected':'' }}>Phone</option><option value="website" {{ request('source')=='website'?'selected':'' }}>Website</option><option value="referral" {{ request('source')=='referral'?'selected':'' }}>Referral</option></select></div>
        </form>
    </div>
    <div class="card-body p-0">
        <x-table :headers="['#','Name','Phone','Email','Source','Status','Follow Up','Actions']">
            @forelse($leads as $l)
            <tr>
                <td>{{ $loop->iteration + ($leads->currentPage()-1)*$leads->perPage() }}</td>
                <td class="fw-semibold">{{ $l->name }}</td>
                <td>{{ $l->phone }}</td>
                <td>{{ $l->email ?? '-' }}</td>
                <td>{{ $l->source ?? '-' }}</td>
                <td><span class="badge bg-{{ $l->status=='converted'?'success':($l->status=='contacted'?'warning':'primary') }}">{{ ucfirst($l->status) }}</span></td>
                <td>{{ $l->follow_up_date ? \Carbon\Carbon::parse($l->follow_up_date)->format('M d, Y') : '-' }}</td>
                <td>
                    <div class="table-actions">
                        @if($l->status!='converted')
                        <form method="POST" action="{{ route('admissions.lead-management.convert', $l->id) }}" class="d-inline" onsubmit="return confirm('Convert to application?')">@csrf<button class="btn btn-sm btn-outline-success"><i class="fas fa-exchange-alt"></i></button></form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted py-4">No leads found</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$leads" />
@endsection
