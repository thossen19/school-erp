@extends("layouts.app")

@section("title", "Fee Discounts")

@section("content")
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Fee Discounts</h1>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Type</th>
                            <th>Value</th>
                            <th>Max Amount</th>
                            <th>Valid From</th>
                            <th>Valid Until</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($discounts as $d)
                            <tr>
                                <td>{{ $d->name }}</td>
                                <td><code>{{ $d->code }}</code></td>
                                <td>{{ ucfirst($d->type) }}</td>
                                <td>
                                    @if($d->is_percentage)
                                        {{ $d->value }}%
                                    @else
                                        ${{ number_format($d->value, 2) }}
                                    @endif
                                </td>
                                <td>{{ $d->max_discount_amount ? '$' . number_format($d->max_discount_amount, 2) : '-' }}</td>
                                <td>{{ $d->valid_from?->format('d-m-Y') ?? $d->valid_from }}</td>
                                <td>{{ $d->valid_until?->format('d-m-Y') ?? $d->valid_until }}</td>
                                <td>
                                    @if($d->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-3">No discounts found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <x-pagination :paginator="$discounts" />
    </div>
</div>
@endsection