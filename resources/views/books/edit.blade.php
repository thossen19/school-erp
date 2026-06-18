@extends('layouts.app')

@section('title', 'Edit Book')

@section('content')
<div class="page-header">
    <div>
        <h4 class="fw-semibold mb-1"><i class="fas fa-book me-2"></i>Edit Book</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Books</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('books.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i>Back</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="POST" action="{{ route('books.update', $book->id) }}">
            @csrf @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $book->title) }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Author <span class="text-danger">*</span></label>
                    <input type="text" name="author" class="form-control @error('author') is-invalid @enderror" value="{{ old('author', $book->author) }}" required>
                    @error('author')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">ISBN</label>
                    <input type="text" name="isbn" class="form-control @error('isbn') is-invalid @enderror" value="{{ old('isbn', $book->isbn) }}">
                    @error('isbn')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Publisher</label>
                    <input type="text" name="publisher" class="form-control @error('publisher') is-invalid @enderror" value="{{ old('publisher', $book->publisher) }}">
                    @error('publisher')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Category</label>
                    <select name="book_category_id" class="form-select @error('book_category_id') is-invalid @enderror">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('book_category_id', $book->book_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('book_category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Edition</label>
                    <input type="text" name="edition" class="form-control @error('edition') is-invalid @enderror" value="{{ old('edition', $book->edition) }}">
                    @error('edition')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Publication Year</label>
                    <input type="number" name="publication_year" class="form-control @error('publication_year') is-invalid @enderror" value="{{ old('publication_year', $book->publication_year) }}" min="1900" max="{{ date('Y') }}">
                    @error('publication_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Language</label>
                    <select name="language" class="form-select @error('language') is-invalid @enderror">
                        <option value="">Select</option>
                        @foreach(['English','Spanish','French','German','Chinese','Japanese','Arabic','Hindi','Bangla','Other'] as $lang)
                            <option value="{{ $lang }}" {{ old('language', $book->language) == $lang ? 'selected' : '' }}>{{ $lang }}</option>
                        @endforeach
                    </select>
                    @error('language')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Pages</label>
                    <input type="number" name="pages" class="form-control @error('pages') is-invalid @enderror" value="{{ old('pages', $book->pages) }}" min="1">
                    @error('pages')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $book->quantity) }}" required min="1">
                    @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Available Quantity <span class="text-danger">*</span></label>
                    <input type="number" name="available_quantity" class="form-control @error('available_quantity') is-invalid @enderror" value="{{ old('available_quantity', $book->available_quantity) }}" required min="0">
                    @error('available_quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Barcode</label>
                    <div class="input-group">
                        <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror" value="{{ old('barcode', $book->barcode) }}" id="barcodeField">
                        <button type="button" class="btn btn-outline-secondary" onclick="generateBarcode()" title="Generate Barcode"><i class="fas fa-qrcode"></i></button>
                        @error('barcode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Price</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $book->price) }}" min="0">
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Rack No.</label>
                    <input type="text" name="rack_number" class="form-control @error('rack_number') is-invalid @enderror" value="{{ old('rack_number', $book->rack_number) }}">
                    @error('rack_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Shelf No.</label>
                    <input type="text" name="shelf_number" class="form-control @error('shelf_number') is-invalid @enderror" value="{{ old('shelf_number', $book->shelf_number) }}">
                    @error('shelf_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="1" {{ old('status', $book->status ?? '1') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $book->status ?? '1') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $book->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mt-4 text-end">
                <a href="{{ route('books.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Update Book</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function generateBarcode() {
    var chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    var barcode = '';
    for (var i = 0; i < 12; i++) {
        barcode += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('barcodeField').value = barcode;
}
</script>
@endpush
