@extends("layouts.app")

@section("title", "Barcode Labels")

@section("content")
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-barcode me-2"></i>Barcode Labels</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Library</li><li class="breadcrumb-item active">Barcode</li></ol></nav>
    </div>
</div>

<div class="filter-bar">
    <form method="GET" action="{{ route('library.barcode') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Title, ISBN or barcode..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary btn-sm w-100"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['#','Title','ISBN','Barcode','Actions']">
            @forelse($books as $book)
                <tr>
                    <td>{{ $book->id }}</td>
                    <td class="fw-semibold">{{ $book->title }}</td>
                    <td>{{ $book->isbn ?? '-' }}</td>
                    <td>
                        @if($book->barcode)
                            <span class="font-monospace">{{ $book->barcode }}</span>
                        @else
                            <span class="text-muted">Not generated</span>
                        @endif
                    </td>
                    <td>
                        <a href="#" class="btn btn-sm btn-outline-secondary" title="Print Label"><i class="fas fa-print"></i></a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-3">No books found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$books" />
</div>
@endsection
