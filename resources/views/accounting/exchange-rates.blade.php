@extends('layouts.app')
@section('title', 'Exchange Rates')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-exchange-alt me-2"></i>Exchange Rates</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Accounting</li><li class="breadcrumb-item active">Exchange Rates</li></ol></nav>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show py-2"><i class="fas fa-check-circle me-1"></i>{{ session('success') }}<button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button></div>
@endif

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-0">
        <x-table :headers="['Currency','Rate','Date','Source','Actions']">
            @forelse($rates as $r)
                <tr>
                    <td class="fw-bold font-monospace">{{ $r->currency_code }}</td>
                    <td class="fw-bold text-end">{{ number_format($r->rate, 6) }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->date)->format('d-m-Y') }}</td>
                    <td>{{ $r->source ?? '-' }}</td>
                    <td>
                        <form method="POST" action="{{ route('accounting.exchange-rates') }}" class="d-inline" onsubmit="return confirm('Delete this rate?')">
                            @csrf
                            <input type="hidden" name="delete" value="{{ $r->id }}">
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-3">No exchange rates found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$rates" />
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-plus me-1"></i>Add Exchange Rate</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('accounting.exchange-rates') }}" class="row g-3">
            @csrf
            <div class="col-md-3">
                <label class="form-label fw-semibold">Currency <span class="text-danger">*</span></label>
                <select name="currency_code" class="form-select" required>
                    <option value="">Select Currency</option>
                    @foreach($currencies as $c)
                        <option value="{{ $c->code }}" {{ old('currency_code') === $c->code ? 'selected' : '' }}>{{ $c->code }} - {{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Rate <span class="text-danger">*</span></label>
                <input type="number" step="0.000001" name="rate" class="form-control" value="{{ old('rate') }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
                <input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Source</label>
                <input type="text" name="source" class="form-control" maxlength="50" value="{{ old('source', 'Manual') }}">
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Add Rate</button>
            </div>
        </form>
    </div>
</div>
@endsection
