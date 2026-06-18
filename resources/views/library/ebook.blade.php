@extends('layouts.app')

@section('title', 'E-Books')

@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-book-reader me-2"></i>E-Books</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item">Library</li><li class="breadcrumb-item active">E-Book</li></ol></nav>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body text-center py-5">
        <i class="fas fa-book-reader fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">E-Book Management</h5>
        <p class="text-muted mb-4">Upload and manage digital books in PDF, EPUB, or other formats.</p>
        <a href="#" class="btn btn-primary"><i class="fas fa-upload me-1"></i>Upload E-Book</a>
    </div>
</div>
@endsection
