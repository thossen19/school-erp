@extends("layouts.app")

@section("title", "Books")

@section("content")
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-book me-2"></i>Books</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item active">Books</li></ol></nav>
    </div>
    <a href="{{ route('books.create') }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Add New Book">+ Add New</a>
</div>

<div class="filter-bar">
    <form method="GET" action="{{ route('books.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Search</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Title, author or ISBN..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">Category</label>
                <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-primary btn-sm w-100" data-bs-toggle="tooltip" title="Search"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </form>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <x-table :headers="['Title','Author','ISBN','Barcode','Category','Total','Available','Actions']">
            @forelse($books as $book)
                <tr>
                    <td class="fw-semibold">{{ $book->title }}</td>
                    <td>{{ $book->author }}</td>
                    <td>{{ $book->isbn ?? '-' }}</td>
                    <td><code>{{ $book->barcode ?? '-' }}</code></td>
                    <td>{{ $book->category?->name ?? '-' }}</td>
                    <td>{{ $book->quantity }}</td>
                    <td><span class="badge bg-{{ $book->available_quantity > 0 ? 'success' : 'danger' }}">{{ $book->available_quantity }}</span></td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('books.show', $book->id) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="View Details"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('books.edit', $book->id) }}" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Edit Book"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this book?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Delete Book"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-3">No books found.</td></tr>
            @endforelse
        </x-table>
    </div>
    <x-pagination :paginator="$books" />
</div>
@endsection
