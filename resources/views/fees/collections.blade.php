@extends("layouts.app")

@section("title", "Fee Collections")

@section("content")
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Fee Collections</h1>
        <a href="{{ route('fees.create') }}" class="btn btn-primary btn-sm">+ Collect Fee</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-auto">
                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" placeholder="From">
                </div>
                <div class="col-auto">
                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" placeholder="To">
                </div>
                <div class="col-auto">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div class="col-auto">
                    <select name="payment_mode" class="form-select form-select-sm">
                        <option value="">All Modes</option>
                        <option value="cash" {{ request('payment_mode') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="cheque" {{ request('payment_mode') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                        <option value="online_transfer" {{ request('payment_mode') == 'online_transfer' ? 'selected' : '' }}>Online Transfer</option>
                        <option value="bank_deposit" {{ request('payment_mode') == 'bank_deposit' ? 'selected' : '' }}>Bank Deposit</option>
                        <option value="card" {{ request('payment_mode') == 'card' ? 'selected' : '' }}>Card</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
                    <a href="{{ route('fees.collection') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Receipt No</th>
                            <th>Student</th>
                            <th>Amount</th>
                            <th>Paid</th>
                            <th>Balance</th>
                            <th>Date</th>
                            <th>Mode</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($collections as $c)
                            <tr>
                                <td>{{ $c->receipt_no ?? $c->receipt_number ?? '-' }}</td>
                                <td>{{ $c->student?->first_name }} {{ $c->student?->last_name }}</td>
                                <td>{{ number_format($c->amount, 2) }}</td>
                                <td>{{ number_format($c->paid_amount, 2) }}</td>
                                <td>{{ number_format($c->balance_amount ?? 0, 2) }}</td>
                                <td>{{ $c->payment_date?->format('d-m-Y') ?? $c->payment_date }}</td>
                                <td>{{ ucfirst($c->payment_mode ?? '-') }}</td>
                                <td>
                                    @php
                                        $badge = match($c->status) {
                                            'paid' => 'bg-success',
                                            'partial' => 'bg-warning text-dark',
                                            'pending' => 'bg-info',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ ucfirst($c->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-3">No collections found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <x-pagination :paginator="$collections" />
    </div>
</div>
@endsection