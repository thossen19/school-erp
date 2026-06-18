@extends('layouts.app')

@section('title', 'Book Issue Details')

@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-book-open me-2"></i>Book Issue Details</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('book-issues.index') }}">Book Issues</a></li><li class="breadcrumb-item active">#{{ $issue->id }}</li></ol></nav>
    </div>
    <a href="{{ route('book-issues.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i>Back</a>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3"><h5 class="mb-0 fw-semibold">Issue Information</h5></div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4 text-muted small">Book</div>
                    <div class="col-md-8 fw-medium">{{ $issue->book?->title ?? '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted small">Member</div>
                    <div class="col-md-8 fw-medium">{{ $issue->libraryMember?->student?->first_name }} {{ $issue->libraryMember?->student?->last_name ?? '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted small">Issue Date</div>
                    <div class="col-md-8">{{ $issue->issue_date?->format('d M Y') ?? '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted small">Due Date</div>
                    <div class="col-md-8">{{ $issue->due_date?->format('d M Y') ?? '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted small">Return Date</div>
                    <div class="col-md-8">{{ $issue->return_date?->format('d M Y') ?? 'Not returned' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted small">Status</div>
                    <div class="col-md-8">
                        @php
                            $badge = match($issue->status) {
                                'returned' => 'success',
                                'overdue' => 'danger',
                                'lost' => 'dark',
                                default => 'warning',
                            };
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ ucfirst($issue->status) }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted small">Fine</div>
                    <div class="col-md-8">{{ $issue->fine_amount > 0 ? '৳' . number_format($issue->fine_amount, 2) : 'None' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted small">Issued By</div>
                    <div class="col-md-8">{{ $issue->issuedBy?->name ?? '-' }}</div>
                </div>
                <div class="row">
                    <div class="col-md-4 text-muted small">Remarks</div>
                    <div class="col-md-8">{{ $issue->remarks ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    @if($issue->status !== 'returned')
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3"><h5 class="mb-0 fw-semibold">Return Book</h5></div>
                <div class="card-body">
                    <form action="{{ route('book-issues.return', $issue->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Remarks</label>
                            <textarea name="remarks" class="form-control" rows="3" placeholder="Condition notes..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100"><i class="fas fa-undo me-1"></i>Mark as Returned</button>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
