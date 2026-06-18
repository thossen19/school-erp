@extends('layouts.app')

@section('title', $book->title)

@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-book me-2"></i>{{ $book->title }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Books</a></li>
                <li class="breadcrumb-item active">Details</li>
            </ol>
        </nav>
    </div>
    <div>
        <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit me-1"></i>Edit</a>
        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i>Back</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold"><i class="fas fa-info-circle me-2"></i>Book Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th class="bg-light" style="width:180px">Title</th>
                        <td>{{ $book->title }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Author</th>
                        <td>{{ $book->author }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">ISBN</th>
                        <td>{{ $book->isbn ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Barcode</th>
                        <td>{{ $book->barcode ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Publisher</th>
                        <td>{{ $book->publisher ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Category</th>
                        <td>{{ $book->category?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Edition</th>
                        <td>{{ $book->edition ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Publication Year</th>
                        <td>{{ $book->publication_year ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Language</th>
                        <td>{{ $book->language ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Pages</th>
                        <td>{{ $book->pages ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Price</th>
                        <td>{{ $book->price ? '$' . number_format($book->price, 2) : '-' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Rack / Shelf</th>
                        <td>{{ $book->rack_number ?? '-' }} / {{ $book->shelf_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Description</th>
                        <td>{{ $book->description ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold"><i class="fas fa-chart-simple me-2"></i>Inventory</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Total Quantity</span>
                    <span class="fw-bold fs-5">{{ $book->quantity }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Available</span>
                    <span class="fw-bold fs-5 text-{{ $book->available_quantity > 0 ? 'success' : 'danger' }}">{{ $book->available_quantity }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Issued</span>
                    <span class="fw-bold fs-5 text-warning">{{ $book->issues_count }}</span>
                </div>
                <hr>
                <div class="progress" style="height:10px">
                    @php $pct = $book->quantity > 0 ? round(($book->available_quantity / $book->quantity) * 100) : 0; @endphp
                    <div class="progress-bar bg-{{ $pct > 50 ? 'success' : ($pct > 25 ? 'warning' : 'danger') }}" style="width:{{ $pct }}%">{{ $pct }}%</div>
                </div>
                <p class="text-muted small mt-2 mb-0">{{ $pct }}% available</p>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold"><i class="fas fa-tags me-2"></i>Status</h5>
            </div>
            <div class="card-body text-center">
                @if($book->status)
                    <span class="badge bg-success fs-6 px-4 py-2">Active</span>
                @else
                    <span class="badge bg-secondary fs-6 px-4 py-2">Inactive</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
