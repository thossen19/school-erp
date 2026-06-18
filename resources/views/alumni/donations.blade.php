@extends('layouts.app')
@section('title', 'Alumni Donations')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-hand-holding-usd me-2"></i>Alumni Donations</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Alumni</li><li class="breadcrumb-item active">Donations</li></ol></nav>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fas fa-plus me-1"></i>Record Donation</button>
</div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label fw-semibold small">Search</label><input type="text" name="search" class="form-control form-control-sm" placeholder="Name or purpose..." value="{{ request('search') }}"></div>
            <div class="col-md-2"><label class="form-label fw-semibold small">Mode</label><select name="payment_mode" class="form-select form-select-sm"><option value="">All</option>@foreach($modes as $m)<option value="{{ $m }}" {{ request('payment_mode') == $m ? 'selected' : '' }}>{{ $m ?? 'N/A' }}</option>@endforeach</select></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i>Filter</button>@if(request('search')||request('payment_mode'))<a href="{{ route('alumni.donations') }}" class="btn btn-outline-secondary btn-sm ms-1"><i class="fas fa-times"></i></a>@endif</div>
        </form>
    </div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Alumnus','Amount','Date','Purpose','Mode','Actions']">
            @forelse($donations as $d)
            <tr>
                <td>{{ $d->id }}</td>
                <td class="fw-semibold">{{ $d->first_name }} {{ $d->last_name }}<br><small class="text-muted">{{ $d->alumnus_email }}</small></td>
                <td class="fw-bold">{{ number_format($d->amount, 2) }}</td>
                <td>{{ $d->donation_date }}</td>
                <td><small>{{ $d->purpose ?? '-' }}</small></td>
                <td>{{ $d->payment_mode ?? '-' }}</td>
                <td>
                    <form method="POST" action="{{ route('alumni.donations.delete', $d->id) }}" class="d-inline" onsubmit="return confirm('Delete this donation record?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center py-4 text-muted">No donations recorded.</td></tr>
            @endforelse
        </x-table>
    </div>
</div>
<x-pagination :paginator="$donations" />

<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="{{ route('alumni.donations.store') }}">@csrf
            <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-1"></i>Record Donation</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Alumnus</label><select name="alumni_id" class="form-select" required><option value="">Select</option>@foreach($alumniList as $al)<option value="{{ $al->id }}">{{ $al->first_name }} {{ $al->last_name }}</option>@endforeach</select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Amount</label><input type="number" step="0.01" name="amount" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Donation Date</label><input type="date" name="donation_date" class="form-control" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Payment Mode</label><select name="payment_mode" class="form-select"><option value="">Select</option><option>Cash</option><option>Bank Transfer</option><option>Cheque</option><option>Online</option></select></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Purpose</label><input type="text" name="purpose" class="form-control"></div>
                    <div class="col-12"><label class="form-label fw-semibold">Remarks</label><textarea name="remarks" class="form-control" rows="2"></textarea></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save</button>
            </div>
        </form>
    </div></div>
</div>
@endsection