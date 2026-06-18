@extends("layouts.app")

@section("title", "Book Issues")

@section("content")
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-book-open me-2"></i>Book Issues</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Book Issues</li></ol></nav>
    </div>
    <a href="{{ route('book-issues.create') }}" class="btn btn-primary btn-sm">+ Issue Book</a>
</div>

<div class="filter-bar">
    <form method="GET" action="{{ route('book-issues.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Status</label>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="issued" {{ request('status') == 'issued' ? 'selected' : '' }}>Issued</option>
                    <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Book','Member','Issue Date','Due Date','Return Date','Status','Fine','Actions']">
            @forelse($issues as $issue)
                <tr>
                    <td>{{ $issue->book?->title ?? '-' }}</td>
                    <td>{{ $issue->libraryMember?->student?->first_name }} {{ $issue->libraryMember?->student?->last_name ?? '-' }}</td>
                    <td>{{ $issue->issue_date?->format('d M Y') ?? '-' }}</td>
                    <td>{{ $issue->due_date?->format('d M Y') ?? '-' }}</td>
                    <td>{{ $issue->return_date?->format('d M Y') ?? '-' }}</td>
                    <td>
                        @php
                            $badge = match($issue->status) {
                                'returned' => 'success',
                                'overdue' => 'danger',
                                'lost' => 'dark',
                                default => 'warning',
                            };
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ ucfirst($issue->status) }}</span>
                    </td>
                    <td>{{ $issue->fine_amount > 0 ? '৳' . number_format($issue->fine_amount, 2) : '-' }}</td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('book-issues.show', $issue->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                            @if($issue->status !== 'returned')
                                <form action="{{ route('book-issues.return', $issue->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Mark as returned?')">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-success" title="Return"><i class="fas fa-undo"></i></button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-3">No book issues found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$issues" />
</div>
@endsection
