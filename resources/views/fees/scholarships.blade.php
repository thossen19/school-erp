@extends("layouts.app")

@section("title", "Scholarships")

@section("content")
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Scholarships / Concessions</h1>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Type</th>
                            <th>Discount</th>
                            <th>Reason</th>
                            <th>Valid From</th>
                            <th>Valid Until</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($scholarships as $s)
                            <tr>
                                <td>{{ $s->student?->first_name }} {{ $s->student?->last_name }}</td>
                                <td>{{ $s->discount_type ?? '-' }}</td>
                                <td>{{ $s->discount_value ? $s->discount_value . '%' : ($s->concession_percentage ? $s->concession_percentage . '%' : '-') }}</td>
                                <td>{{ $s->reason ?? '-' }}</td>
                                <td>{{ $s->valid_from?->format('d-m-Y') ?? '-' }}</td>
                                <td>{{ $s->valid_until?->format('d-m-Y') ?? '-' }}</td>
                                <td>
                                    @php
                                        $badge = match($s->status) {
                                            'active' => 'bg-success',
                                            'expired' => 'bg-secondary',
                                            default => 'bg-info',
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ ucfirst($s->status ?? 'active') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-3">No scholarships / concessions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <x-pagination :paginator="$scholarships" />
    </div>
</div>
@endsection