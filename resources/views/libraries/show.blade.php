@extends('layouts.app')
@section('title', 'Book Detail')
@section('content')
<div class="page-header">
    <div><h4 class="fw-semibold mb-1"><i class="fas fa-book me-2"></i>Book Detail</h4>
        <nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li><li class="breadcrumb-item"><a href="{{ route('library.index') }}">Library</a></li><li class="breadcrumb-item active">To Kill a Mockingbird</li></ol></nav>
    </div>
    <div class="d-flex gap-2"><a href="{{ route('library.edit',1) }}" class="btn btn-primary"><i class="fas fa-edit me-1"></i>Edit</a></div>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3"><small class="text-muted d-block">ISBN</small><span>978-0-06-112008-4</span></div>
            <div class="col-md-3"><small class="text-muted d-block">Title</small><span class="fw-semibold">To Kill a Mockingbird</span></div>
            <div class="col-md-3"><small class="text-muted d-block">Author</small><span>Harper Lee</span></div>
            <div class="col-md-3"><small class="text-muted d-block">Category</small><span class="badge bg-info">Fiction</span></div>
            <div class="col-md-3"><small class="text-muted d-block">Publisher</small><span>J.B. Lippincott & Co.</span></div>
            <div class="col-md-3"><small class="text-muted d-block">Year</small><span>1960</span></div>
            <div class="col-md-3"><small class="text-muted d-block">Total Copies</small><span class="fw-bold">5</span></div>
            <div class="col-md-3"><small class="text-muted d-block">Available</small><span class="fw-bold text-success">3</span></div>
            <div class="col-md-3"><small class="text-muted d-block">Issued</small><span class="fw-bold text-warning">2</span></div>
            <div class="col-md-3"><small class="text-muted d-block">Rack</small><span>A-1</span></div>
            <div class="col-12"><small class="text-muted d-block">Description</small><span>A classic novel about racial injustice in the American South.</span></div>
        </div>
    </div>
</div>
@endsection