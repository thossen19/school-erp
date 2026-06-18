@extends('layouts.app')

@section('title', 'Fee Collection Details')

@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-bold mb-0">Fee Collection Details</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route_if_exists('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route_if_exists('fees.index') }}">Fee Collections</a></li>
                <li class="breadcrumb-item active">Details</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <p>Receipt No: {{ $record->receipt_no ?? 'N/A' }}</p>
        <p>Amount: {{ number_format($record->total_amount, 2) }}</p>
        <p>Status: {{ ucfirst($record->status) }}</p>
    </div>
</div>
@endsection
