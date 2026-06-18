@extends('layouts.app')

@section('title', 'Fee Collections')

@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-bold mb-0">Fee Collections</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route_if_exists('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Fee Collections</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route_if_exists('fees.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i>Collect Fee</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Receipt No</th>
                        <th>Student</th>
                        <th>Amount</th>
                        <th>Paid</th>
                        <th>Balance</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($collections as $collection)
                    <tr>
                        <td>{{ $collection->receipt_no ?? 'N/A' }}</td>
                        <td>{{ $collection->student?->first_name }} {{ $collection->student?->last_name }}</td>
                        <td>{{ number_format($collection->total_amount, 2) }}</td>
                        <td>{{ number_format($collection->paid_amount, 2) }}</td>
                        <td>{{ number_format($collection->balance_amount, 2) }}</td>
                        <td>{{ $collection->payment_date?->format('d/m/Y') }}</td>
                        <td><span class="badge bg-{{ $collection->status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($collection->status) }}</span></td>
                        <td>
                            <a href="{{ route_if_exists('fees.show', $collection->id) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center">No fee collections found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <x-pagination :paginator="$collections" />
    </div>
</div>
@endsection
