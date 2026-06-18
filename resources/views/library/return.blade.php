@extends('layouts.app')

@section('title', 'Return Book')

@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-undo me-2"></i>Return Book</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Library</li><li class="breadcrumb-item active">Return</li></ol></nav>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Book','Member','Issue Date','Due Date','Days Overdue','Action']">
            @forelse($issues as $issue)
                @php
                    $overdueDays = $issue->due_date && $issue->due_date->isPast() ? $issue->due_date->diffInDays(now()) : 0;
                @endphp
                <tr class="{{ $overdueDays > 0 ? 'table-warning' : '' }}">
                    <td class="fw-semibold">{{ $issue->book?->title ?? '-' }}</td>
                    <td>{{ $issue->libraryMember?->student?->first_name }} {{ $issue->libraryMember?->student?->last_name ?? '-' }}</td>
                    <td>{{ $issue->issue_date?->format('d M Y') ?? '-' }}</td>
                    <td>{{ $issue->due_date?->format('d M Y') ?? '-' }}</td>
                    <td>
                        @if($overdueDays > 0)
                            <span class="text-danger fw-semibold">{{ $overdueDays }} day{{ $overdueDays > 1 ? 's' : '' }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('book-issues.return', $issue->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Return this book?')">
                            @csrf
                            <button class="btn btn-sm btn-success"><i class="fas fa-check me-1"></i>Return</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-3">All books have been returned.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$issues" />
</div>
@endsection
