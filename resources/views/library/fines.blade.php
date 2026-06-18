@extends('layouts.app')

@section('title', 'Library Fines')

@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-money-bill me-2"></i>Library Fines</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Library</li><li class="breadcrumb-item active">Fine</li></ol></nav>
    </div>
</div>

<div class="filter-bar">
    <form method="GET" action="{{ route('library.fines') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Status</label>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Fines</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
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
        <x-table :headers="['Book','Member','Fine Amount','Status','Issued','Actions']">
            @forelse($fines as $fine)
                <tr>
                    <td class="fw-semibold">{{ $fine->book_title ?? '-' }}</td>
                    <td>{{ $fine->first_name ?? '' }} {{ $fine->last_name ?? '' }}</td>
                    <td>৳{{ number_format($fine->fine_amount, 2) }}</td>
                    <td>
                        @if($fine->fine_paid)
                            <span class="badge bg-success">Paid</span>
                        @else
                            <span class="badge bg-danger">Unpaid</span>
                        @endif
                    </td>
                    <td>{{ date('d M Y', strtotime($fine->created_at)) }}</td>
                    <td>
                        @if(!$fine->fine_paid)
                            <form action="{{ route('book-issues.index') }}" method="GET" class="d-inline">
                                <button class="btn btn-sm btn-outline-success" title="Mark Paid"><i class="fas fa-check-circle"></i></button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-3">No fines found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$fines" />
</div>
@endsection
